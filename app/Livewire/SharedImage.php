<?php

namespace App\Livewire;

use App\Models\Image;
use Livewire\Component;

class SharedImage extends Component
{
    public Image $image;

    public function mount($shareToken)
    {
        $this->image = Image::with(['prompt', 'user', 'collection'])
            ->where('share_token', $shareToken)
            ->where('is_public', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.shared-image', ['image' => $this->image]);
    }
}
