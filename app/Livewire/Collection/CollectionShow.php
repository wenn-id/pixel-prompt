<?php

namespace App\Livewire\Collection;

use App\Models\Collection;
use App\Models\Image;
use Livewire\Component;
use Livewire\WithPagination;

class CollectionShow extends Component
{
    use WithPagination;

    public Collection $collection;
    public $edit_name = '';
    public $edit_description = '';
    public $editing = false;

    public function mount(Collection $collection)
    {
        abort_if($collection->user_id !== auth()->id(), 404);
        $this->collection = $collection;
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
        if ($this->editing) {
            $this->edit_name = $this->collection->name;
            $this->edit_description = $this->collection->description;
        }
    }

    public function save()
    {
        $this->validate(['edit_name' => 'required|max:100']);
        $this->collection->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
        ]);
        $this->editing = false;
        $this->dispatch('saved');
    }

    public function removeImage($imageId)
    {
        $img = Image::where('user_id', auth()->id())
            ->where('collection_id', $this->collection->id)
            ->findOrFail($imageId);
        $img->update(['collection_id' => null]);
    }

    public function delete()
    {
        $this->collection->delete();
        return redirect()->route('collections.index');
    }

    public function render()
    {
        $images = Image::with('prompt')
            ->where('user_id', auth()->id())
            ->where('collection_id', $this->collection->id)
            ->latest()
            ->paginate(24);

        return view('livewire.collection-show', [
            'images' => $images,
            'collection' => $this->collection,
        ]);
    }
}
