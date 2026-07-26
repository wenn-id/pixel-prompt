<div>
    <div class="mb-6">
        <a href="{{ route('collections.index') }}" class="text-sm text-slate-400 hover:text-white transition">&larr; Collections</a>
        <div class="flex items-start justify-between mt-2">
            <div>
                @if($editing)
                <input wire:model="edit_name" type="text" class="text-2xl font-bold bg-slate-800 border-slate-700 text-white rounded-lg px-3 py-1">
                @else
                <h1 class="text-2xl font-bold text-white">{{ $collection->name }}</h1>
                @endif
                <p class="text-sm text-slate-400 mt-1">{{ $images->total() }} images</p>
            </div>
            <div class="flex gap-2">
                <button wire:click="toggleEdit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs text-white rounded-lg transition">
                    {{ $editing ? 'Cancel' : 'Edit' }}
                </button>
                @if($editing)
                <button wire:click="save" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-xs text-white rounded-lg transition">Save</button>
                @endif
                <button wire:click="delete" wire:confirm="Delete this collection?" class="px-3 py-1.5 bg-red-900/30 hover:bg-red-900/50 text-xs text-red-400 rounded-lg transition">Delete</button>
            </div>
        </div>
        @if($editing)
        <textarea wire:model="edit_description" rows="2" class="mt-2 w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-1.5 resize-y"></textarea>
        @elseif($collection->description)
        <p class="text-sm text-slate-400 mt-2">{{ $collection->description }}</p>
        @endif
    </div>

    @if($images->isEmpty())
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center text-slate-400 text-sm">
        No images in this collection yet. Browse your gallery to add images.
    </div>
    @else
    <div class="columns-2 sm:columns-3 lg:columns-4 xl:columns-5 gap-3 space-y-3">
        @foreach($images as $image)
        <div wire:key="col-img-{{ $image->id }}" class="break-inside-avoid group relative rounded-lg overflow-hidden bg-slate-800 hover:ring-2 hover:ring-indigo-500 transition-all">
            <a href="{{ route('gallery.show', $image) }}" class="block">
                <img src="{{ Storage::disk(config('filesystems.default'))->url($image->thumbnail_path ?? $image->file_path) }}"
                     alt="" class="w-full object-cover group-hover:opacity-90 transition-opacity" loading="lazy">
            </a>
            <div class="absolute top-2 right-2">
                <button wire:click="removeImage({{ $image->id }})" wire:confirm="Remove from collection?"
                        class="p-1.5 bg-black/60 hover:bg-red-900/70 rounded-lg text-white text-xs transition opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $images->links() }}</div>
    @endif
</div>