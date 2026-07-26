<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Collections</h1>
        <p class="text-sm text-slate-400 mt-1">Organize your images into albums</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 mb-6">
        <form wire:submit="create" class="flex gap-3">
            <input wire:model="name" type="text" placeholder="Collection name..." required
                   class="flex-1 bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
                Create
            </button>
        </form>
        @error('name') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
    </div>

    @if($collections->isEmpty())
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center">
        <p class="text-slate-400 text-sm">No collections yet.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($collections as $c)
        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-slate-700 transition group">
            <a href="{{ route('collections.show', $c) }}" class="block aspect-[16/9] bg-slate-800 relative overflow-hidden">
                @if($c->coverImage)
                <img src="{{ Storage::disk(config('filesystems.default'))->url($c->coverImage->thumbnail_path ?? $c->coverImage->file_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                @elseif($c->images()->first())
                <img src="{{ Storage::disk(config('filesystems.default'))->url($c->images()->first()->thumbnail_path ?? $c->images()->first()->file_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                @else
                <div class="w-full h-full flex items-center justify-center text-slate-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                @endif
            </a>
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-white font-medium">{{ $c->name }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $c->images_count }} images{{ $c->is_public ? ' · Public' : '' }}</p>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                        <button wire:click="edit({{ $c->id }})" class="p-1.5 text-slate-400 hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg></button>
                        <button wire:click="delete({{ $c->id }})" wire:confirm="Delete this collection?" class="p-1.5 text-red-400 hover:bg-red-900/30 rounded transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button>
                    </div>
                </div>

                @if($editing_id === $c->id)
                <form wire:submit="saveEdit" class="mt-3 space-y-2">
                    <input wire:model="edit_name" type="text" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-1.5">
                    <textarea wire:model="edit_description" rows="2" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-1.5 resize-y" placeholder="Description..."></textarea>
                    <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                        <input wire:model="edit_is_public" type="checkbox" class="rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500"> Public
                    </label>
                    <div class="flex gap-2">
                        <button type="submit" class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg">Save</button>
                        <button type="button" wire:click="cancelEdit" class="px-3 py-1 bg-slate-800 text-slate-300 text-xs rounded-lg">Cancel</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>