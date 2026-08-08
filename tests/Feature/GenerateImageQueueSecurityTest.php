<?php

namespace Tests\Feature;

use App\Jobs\GenerateImage;
use App\Livewire\Prompt\PromptCreate;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class GenerateImageQueueSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_queued_payload_does_not_contain_plaintext_api_key(): void
    {
        Queue::fake();
        $user = User::factory()->create();
        $secret = 'queue-plaintext-secret-marker';

        ApiKey::create([
            'user_id' => $user->id,
            'provider' => 'openai',
            'label' => 'Primary',
            'key_encrypted' => Crypt::encryptString($secret),
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(PromptCreate::class)
            ->set('prompt_text', 'A secure queued image')
            ->set('provider', 'openai')
            ->set('model', 'dall-e-3')
            ->call('generate');

        Queue::assertPushed(GenerateImage::class, function (GenerateImage $job) use ($secret): bool {
            $this->assertStringNotContainsString($secret, serialize($job));

            return true;
        });
    }

    public function test_worker_resolves_and_decrypts_api_key_at_runtime(): void
    {
        $user = User::factory()->create();
        $secret = 'runtime-decryption-secret-marker';
        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'provider' => 'openai',
            'label' => 'Primary',
            'key_encrypted' => Crypt::encryptString($secret),
            'is_active' => true,
        ]);
        $prompt = $user->prompts()->create([
            'prompt_text' => 'A secure queued image',
            'provider' => 'openai',
            'model' => 'dall-e-3',
            'width' => 1024,
            'height' => 1024,
        ]);
        Http::fake([
            'api.openai.com/*' => Http::response([
                'data' => [['url' => 'https://images.example/generated.png']],
            ]),
            'images.example/*' => Http::failedConnection(),
        ]);

        try {
            (new GenerateImage($prompt, $apiKey->id))->handle();
            $this->fail('Expected fake image download to stop the job.');
        } catch (ConnectionException) {
            // Request authorization has already been sent; image processing is out of scope here.
        }

        Http::assertSent(fn ($request): bool => $request->url() === 'https://api.openai.com/v1/images/generations'
            && $request->hasHeader('Authorization', "Bearer {$secret}"));
    }

    public function test_worker_rejects_api_key_owned_by_another_user(): void
    {
        Http::preventStrayRequests();
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $apiKey = ApiKey::create([
            'user_id' => $otherUser->id,
            'provider' => 'openai',
            'label' => 'Other user key',
            'key_encrypted' => Crypt::encryptString('other-user-secret'),
            'is_active' => true,
        ]);
        $prompt = $owner->prompts()->create([
            'prompt_text' => 'Do not cross account boundaries',
            'provider' => 'openai',
            'model' => 'dall-e-3',
        ]);

        $this->expectException(ModelNotFoundException::class);

        (new GenerateImage($prompt, $apiKey->id))->handle();
    }

    public function test_worker_rejects_inactive_api_key(): void
    {
        Http::preventStrayRequests();
        $user = User::factory()->create();
        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'provider' => 'openai',
            'label' => 'Disabled key',
            'key_encrypted' => Crypt::encryptString('disabled-secret'),
            'is_active' => false,
        ]);
        $prompt = $user->prompts()->create([
            'prompt_text' => 'Do not use disabled credentials',
            'provider' => 'openai',
            'model' => 'dall-e-3',
        ]);

        $this->expectException(ModelNotFoundException::class);

        (new GenerateImage($prompt, $apiKey->id))->handle();
    }

    public function test_worker_rejects_api_key_for_another_provider(): void
    {
        Http::preventStrayRequests();
        $user = User::factory()->create();
        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'provider' => 'stability',
            'label' => 'Stability key',
            'key_encrypted' => Crypt::encryptString('wrong-provider-secret'),
            'is_active' => true,
        ]);
        $prompt = $user->prompts()->create([
            'prompt_text' => 'Do not mix provider credentials',
            'provider' => 'openai',
            'model' => 'dall-e-3',
        ]);

        $this->expectException(ModelNotFoundException::class);

        (new GenerateImage($prompt, $apiKey->id))->handle();
    }
}
