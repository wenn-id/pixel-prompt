<?php

namespace App\Livewire\Prompt;

use App\Models\Prompt;
use Livewire\Component;
use Livewire\WithPagination;

class PromptIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $provider = '';

    protected $queryString = ['search', 'provider'];

    public function deletePrompt($promptId)
    {
        $prompt = Prompt::where('user_id', auth()->id())->findOrFail($promptId);
        $prompt->delete();
    }

    public function render()
    {
        $query = Prompt::withCount('images')
            ->where('user_id', auth()->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('prompt_text', 'like', '%'.$this->search.'%')
                  ->orWhere('title', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->provider) {
            $query->where('provider', $this->provider);
        }

        $prompts = $query->latest()->paginate(20);
        $providers = Prompt::where('user_id', auth()->id())
            ->distinct('provider')
            ->pluck('provider')
            ->filter();

        return view('livewire.prompt-index', compact('prompts', 'providers'));
    }
}
