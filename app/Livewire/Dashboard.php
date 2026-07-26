<?php

namespace App\Livewire;

use App\Models\Image;
use App\Models\Prompt;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $stats = [
            'total_images' => Image::where('user_id', $user->id)->count(),
            'total_prompts' => Prompt::where('user_id', $user->id)->count(),
            'total_collections' => Collection::where('user_id', $user->id)->count(),
            'favorites' => Image::where('user_id', $user->id)->where('is_favorite', true)->count(),
        ];

        $recent = Image::with('prompt')
            ->where('user_id', $user->id)
            ->latest()
            ->take(12)
            ->get();

        return view('livewire.dashboard', compact('stats', 'recent'));
    }
}
