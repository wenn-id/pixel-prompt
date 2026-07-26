<?php

namespace App\Livewire\Prompt;

use App\Models\Prompt;
use App\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Component;

class PromptEdit extends Component
{
    public Prompt $prompt;
    public $title;
    public $prompt_text;
    public $negative_prompt;
    public $width;
    public $height;
    public $cfg_scale;
    public $steps;
    public $seed;
    public $style_preset;
    public $tags_input = '';

    public function mount(Prompt $prompt)
    {
        abort_if($prompt->user_id !== auth()->id(), 404);
        $this->fill($prompt);
        $this->tags_input = $prompt->tags->pluck('name')->implode(', ');
    }

    public function save()
    {
        $this->validate(['prompt_text' => 'required|string|max:4000']);

        $this->prompt->update([
            'title' => $this->title,
            'prompt_text' => $this->prompt_text,
            'negative_prompt' => $this->negative_prompt,
            'width' => $this->width,
            'height' => $this->height,
            'cfg_scale' => $this->cfg_scale,
            'steps' => $this->steps,
            'seed' => $this->seed,
            'style_preset' => $this->style_preset,
        ]);

        if ($this->tags_input !== null) {
            $tagIds = [];
            foreach (array_map('trim', explode(',', $this->tags_input)) as $tagName) {
                if ($tagName) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $this->prompt->tags()->sync($tagIds);
        }

        $this->dispatch('saved');
        return redirect()->route('prompts.index')->with('status', 'prompt-updated');
    }

    public function render()
    {
        $this->prompt->load('tags');
        return view('livewire.prompt-edit');
    }
}
