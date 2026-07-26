<?php

namespace App\Livewire\Collection;

use App\Models\Collection;
use App\Models\Image;
use Livewire\Component;

class CollectionIndex extends Component
{
    public $name = '';
    public $description = '';
    public $is_public = false;
    public $editing_id = null;
    public $edit_name = '';
    public $edit_description = '';
    public $edit_is_public = false;

    public function create()
    {
        $this->validate(['name' => 'required|max:100']);
        Collection::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => $this->is_public,
        ]);
        $this->reset(['name', 'description', 'is_public']);
        $this->dispatch('collection-created');
    }

    public function edit($id)
    {
        $c = Collection::where('user_id', auth()->id())->findOrFail($id);
        $this->editing_id = $c->id;
        $this->edit_name = $c->name;
        $this->edit_description = $c->description;
        $this->edit_is_public = $c->is_public;
    }

    public function saveEdit()
    {
        $this->validate(['edit_name' => 'required|max:100']);
        $c = Collection::where('user_id', auth()->id())->findOrFail($this->editing_id);
        $c->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
            'is_public' => $this->edit_is_public,
        ]);
        $this->editing_id = null;
    }

    public function cancelEdit()
    {
        $this->editing_id = null;
    }

    public function delete($id)
    {
        $c = Collection::where('user_id', auth()->id())->findOrFail($id);
        $c->delete();
    }

    public function render()
    {
        $collections = Collection::withCount('images')
            ->where('user_id', auth()->id())
            ->orderBy('sort_order')
            ->get();

        return view('livewire.collection-index', compact('collections'));
    }
}
