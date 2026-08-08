<?php

namespace App\Jobs;

use App\Models\ApiKey;
use App\Models\Image;
use App\Models\Prompt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class GenerateImage implements ShouldQueue
{
    use Queueable;

    public Prompt $prompt;

    public int $apiKeyId;

    public array $params;

    private string $apiKey;

    public int $timeout = 300;

    public int $tries = 2;

    public function __construct(Prompt $prompt, int $apiKeyId, array $params = [])
    {
        $this->prompt = $prompt;
        $this->apiKeyId = $apiKeyId;
        $this->params = $params;
    }

    public function handle(): void
    {
        $startTime = microtime(true);
        $provider = $this->prompt->provider;
        $model = $this->prompt->model;
        $promptText = $this->prompt->prompt_text;
        $this->apiKey = Crypt::decryptString(
            ApiKey::query()
                ->whereKey($this->apiKeyId)
                ->where('user_id', $this->prompt->user_id)
                ->where('provider', $provider)
                ->where('is_active', true)
                ->firstOrFail()
                ->key_encrypted
        );

        $imageData = match ($provider) {
            'openai' => $this->callOpenAI($model, $promptText),
            'replicate' => $this->callReplicate($model, $promptText),
            'stability' => $this->callStability($model, $promptText),
            '9router' => $this->call9Router($model, $promptText),
            default => throw new \Exception("Unknown provider: {$provider}"),
        };

        $genTime = (int) ((microtime(true) - $startTime) * 1000);

        // Download image
        $imageContent = Http::timeout(60)->get($imageData['url'])->body();
        $ext = $imageData['format'] ?? 'png';
        $filename = Str::random(32).'.'.$ext;
        $thumbFilename = 'thumb_'.$filename;

        $disk = Storage::disk(config('filesystems.default'));
        $disk->put("images/{$filename}", $imageContent);

        // Create thumbnail - resize if intervention/image available
        try {
            $img = ImageManager::imagick()->read($imageContent);
            $img->scaleDown(width: 400);
            $disk->put("images/{$thumbFilename}", $img->encodeByExtension($ext, quality: 80));
        } catch (\Exception $e) {
            // Fallback: use original as thumbnail
            $disk->put("images/{$thumbFilename}", $imageContent);
            Log::warning("Thumbnail creation failed: {$e->getMessage()}");
        }

        // Get image dimensions
        $size = getimagesizefromstring($imageContent);
        $width = $size[0] ?? $this->prompt->width;
        $height = $size[1] ?? $this->prompt->height;

        Image::create([
            'user_id' => $this->prompt->user_id,
            'prompt_id' => $this->prompt->id,
            'file_path' => "images/{$filename}",
            'thumbnail_path' => "images/{$thumbFilename}",
            'width' => $width,
            'height' => $height,
            'file_size' => strlen($imageContent),
            'provider' => $provider,
            'model' => $model,
            'parameters' => $this->params,
            'generation_time_ms' => $genTime,
        ]);
    }

    protected function callOpenAI(string $model, string $prompt): array
    {
        $size = $this->getSizeForDalle($this->prompt->width, $this->prompt->height);

        $response = Http::withToken($this->apiKey)
            ->timeout(120)
            ->post('https://api.openai.com/v1/images/generations', [
                'model' => $model,
                'prompt' => $prompt,
                'n' => 1,
                'size' => $size,
            ])->throw();

        $data = $response->json();

        return [
            'url' => $data['data'][0]['url'],
            'format' => 'png',
        ];
    }

    protected function callReplicate(string $model, string $prompt): array
    {
        $response = Http::withToken($this->apiKey)
            ->timeout(180)
            ->post('https://api.replicate.com/v1/predictions', [
                'version' => $model,
                'input' => [
                    'prompt' => $prompt,
                    'width' => $this->prompt->width ?? 1024,
                    'height' => $this->prompt->height ?? 1024,
                    'num_outputs' => 1,
                ],
            ])->throw();

        $prediction = $response->json();
        $predictionId = $prediction['id'];

        // Poll for completion
        $output = null;
        for ($i = 0; $i < 60; $i++) {
            sleep(3);
            $status = Http::withToken($this->apiKey)
                ->get("https://api.replicate.com/v1/predictions/{$predictionId}")
                ->json();

            if ($status['status'] === 'succeeded') {
                $output = $status['output'];
                break;
            }
            if ($status['status'] === 'failed') {
                throw new \Exception('Replicate generation failed: '.($status['error'] ?? 'unknown'));
            }
        }

        if (! $output) {
            throw new \Exception('Replicate generation timed out');
        }

        $url = is_array($output) ? $output[0] : $output;

        return ['url' => $url, 'format' => 'png'];
    }

    protected function callStability(string $model, string $prompt): array
    {
        // Stability AI REST API
        $engine = match ($model) {
            'stable-diffusion-3' => 'stable-diffusion-v3',
            'stable-diffusion-xl' => 'stable-diffusion-xl-1024-v1-0',
            default => $model,
        };

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Accept' => 'application/json',
        ])->timeout(120)
            ->post('https://api.stability.ai/v2beta/stable-image/generate/sd3', [
                'prompt' => $prompt,
                'output_format' => 'png',
                'width' => $this->prompt->width ?? 1024,
                'height' => $this->prompt->height ?? 1024,
                'cfg_scale' => $this->prompt->cfg_scale ?? 7,
                'steps' => $this->prompt->steps ?? 30,
            ])->throw();

        $data = $response->json();

        return [
            'url' => $data['image'] ?? $data['artifacts'][0]['base64'],
            'format' => 'png',
        ];
    }

    protected function call9Router(string $model, string $prompt): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ])->timeout(120)
            ->post('https://api.9router.com/v1/images/generations', [
                'model' => $model,
                'prompt' => $prompt,
                'n' => 1,
                'size' => $this->getSizeForDalle($this->prompt->width, $this->prompt->height),
            ])->throw();

        $data = $response->json();

        return [
            'url' => $data['data'][0]['url'] ?? $data['data'][0]['b64_json'],
            'format' => 'png',
        ];
    }

    protected function getSizeForDalle(?int $width, ?int $height): string
    {
        $w = $width ?? 1024;
        $h = $height ?? 1024;

        if ($w <= 512 && $h <= 512) {
            return '512x512';
        }
        if ($w <= 1024 && $h <= 1024) {
            return '1024x1024';
        }

        return '1792x1024';
    }

    public function failed(\Throwable $e): void
    {
        Log::error('Image generation failed', [
            'prompt_id' => $this->prompt->id,
            'error' => $e->getMessage(),
        ]);
    }
}
