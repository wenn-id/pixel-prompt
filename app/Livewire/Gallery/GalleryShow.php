<?php

namespace App\Livewire\Gallery;

use App\Models\Image;
use Livewire\Component;

class GalleryShow extends Component
{
    public Image $image;

    public function mount(Image $image)
    {
        abort_if($image->user_id !== auth()->id(), 404);
        $this->image = $image->load(['prompt', 'collection', 'tags']);
    }

    public function toggleFavorite()
    {
        $this->image->update(['is_favorite' => !$this->image->is_favorite]);
    }

    public function togglePublic()
    {
        $this->image->update(['is_public' => !$this->image->is_public]);
    }

    public function delete()
    {
        $this->image->delete();
        return redirect()->route('gallery.index');
    }

    public function render()
    {
        return view('livewire.gallery-show');
    }
}
