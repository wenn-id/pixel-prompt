<?php

namespace App\Livewire\Prompt;

use App\Models\ApiKey;
use App\Models\Image;
use App\Models\Prompt;
use App\Models\Tag;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Str;

class PromptCreate extends Component
{
    public $title = '';
    public $prompt_text = '';
    public $negative_prompt = '';
    public $provider = '';
    public $model = '';
    public $width = 1024;
    public $height = 1024;
    public $cfg_scale = 7.0;
    public $steps = 30;
    public $seed;
    public $style_preset = '';
    public $tags_input = '';
    public $is_generating = false;
    public $generated_image_id = null;
    public $generation_error = null;

    public $available_models = [
        'openai' => ['dall-e-3', 'dall-e-2'],
        'replicate' => ['black-forest-labs/flux-schnell', 'black-forest-labs/flux-pro', 'stability-ai/stable-diffusion-3'],
        'stability' => ['stable-diffusion-3', 'stable-diffusion-xl'],
        '9router' => ['cx/gpt-5.5-image'],
    ];

    public function updatedProvider()
    {
        $this->model = '';
    }

    public function generate()
    {
        $this->validate([
            'prompt_text' => 'required|string|max:4000',
            'provider' => 'required|string',
            'model' => 'required|string',
        ]);

        $this->is_generating = true;
        $this->generation_error = null;
        $this->generated_image_id = null;

        try {
            $apiKey = ApiKey::where('user_id', auth()->id())
                ->where('provider', $this->provider)
                ->where('is_active', true)
                ->firstOrFail();

            // Create the prompt record first
            $prompt = Prompt::create([
                'user_id' => auth()->id(),
                'title' => $this->title ?: substr($this->prompt_text, 0, 60),
                'prompt_text' => $this->prompt_text,
                'negative_prompt' => $this->negative_prompt,
                'width' => $this->width,
                'height' => $this->height,
                'cfg_scale' => $this->cfg_scale,
                'steps' => $this->steps,
                'seed' => $this->seed,
                'style_preset' => $this->style_preset,
                'provider' => $this->provider,
                'model' => $this->model,
            ]);

            // Apply tags
            if ($this->tags_input) {
                $tagNames = array_map('trim', explode(',', $this->tags_input));
                foreach ($tagNames as $tagName) {
                    if ($tagName) {
                        $tag = Tag::firstOrCreate(
                            ['slug' => Str::slug($tagName)],
                            ['name' => $tagName]
                        );
                        $prompt->tags()->syncWithoutDetaching([$tag->id]);
                    }
                }
            }

            // Dispatch generation job
            $job = new \App\Jobs\GenerateImage(
                $prompt,
                Crypt::decryptString($apiKey->key_encrypted),
                [
                    'width' => $this->width,
                    'height' => $this->height,
                    'cfg_scale' => $this->cfg_scale,
                    'steps' => $this->steps,
                    'seed' => $this->seed,
                ]
            );
            dispatch($job);

            $this->dispatch('generation-started', promptId: $prompt->id);
            return redirect()->route('gallery.index')->with('status', 'generation-queued');
        } catch (\Exception $e) {
            $this->generation_error = $e->getMessage();
            $this->is_generating = false;
        }
    }

    public function render()
    {
        $keys = ApiKey::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get()
            ->groupBy('provider');

        return view('livewire.prompt-create', compact('keys'));
    }
}
