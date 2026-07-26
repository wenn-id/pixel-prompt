<?php

namespace App\Livewire\Gallery;

use App\Models\Image;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $provider = '';
    public $tag = '';
    public $favorites = false;
    public $sort = 'latest';

    protected $queryString = ['search', 'provider', 'tag', 'favorites', 'sort'];

    public function toggleFavorite($imageId)
    {
        $image = Image::where('user_id', auth()->id())->findOrFail($imageId);
        $image->update(['is_favorite' => !$image->is_favorite]);
    }

    public function deleteImage($imageId)
    {
        $image = Image::where('user_id', auth()->id())->findOrFail($imageId);
        $image->delete();
        $this->dispatch('image-deleted');
    }

    public function render()
    {
        $user = auth()->user();
        $query = Image::with(['prompt:id,prompt_text', 'tags'])
            ->where('user_id', $user->id);

        if ($this->search) {
            $query->whereHas('prompt', function ($q) {
                $q->where('prompt_text', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->provider) {
            $query->where('provider', $this->provider);
        }

        if ($this->tag) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $this->tag));
        }

        if ($this->favorites) {
            $query->where('is_favorite', true);
        }

        $sortMap = [
            'latest' => ['column' => 'created_at', 'dir' => 'desc'],
            'oldest' => ['column' => 'created_at', 'dir' => 'asc'],
            'favorite' => ['column' => 'is_favorite', 'dir' => 'desc'],
        ];
        $sort = $sortMap[$this->sort] ?? $sortMap['latest'];
        $query->orderBy($sort['column'], $sort['dir']);

        if ($this->sort === 'favorite') {
            $query->latest();
        }

        $images = $query->paginate(24);
        $providers = Image::where('user_id', $user->id)
            ->select('provider')
            ->distinct()
            ->pluck('provider')
            ->filter();
        $tags = Tag::whereHas('images', fn($q) => $q->where('user_id', $user->id))
            ->orderBy('name')
            ->get();

        return view('livewire.gallery-index', compact('images', 'providers', 'tags'));
    }
}
