<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Gallery</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $images->total() }} images</p>
        </div>
        <a href="{{ route('prompts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Prompt
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search prompts..."
                       class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
            </div>
            <select wire:model.live="provider" class="bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">All providers</option>
                @foreach($providers as $p)
                <option value="{{ $p }}">{{ ucfirst($p) }}</option>
                @endforeach
            </select>
            <select wire:model.live="sort" class="bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
                <option value="favorite">Favorites first</option>
            </select>
        </div>
        <div class="flex flex-wrap items-center gap-2 mt-3">
            <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer">
                <input wire:model.live="favorites" type="checkbox" class="rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                Favorites only
            </label>
            @if($tags->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 ml-auto">
                @foreach($tags->take(8) as $t)
                <button wire:click="$set('tag', '{{ $tag === $t->slug ? '' : $t->slug }}')"
                        @class([
                            'px-2 py-0.5 rounded-full text-xs transition',
                            'bg-indigo-600 text-white' => $tag === $t->slug,
                            'bg-slate-800 text-slate-400 hover:bg-slate-700' => $tag !== $t->slug,
                        ])>{{ $t->name }}</button>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    @if($images->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center">
            <p class="text-slate-400 text-sm">No images match your filters.</p>
        </div>
    @else
        <div class="columns-2 sm:columns-3 lg:columns-4 xl:columns-5 gap-3 space-y-3">
            @foreach($images as $image)
            <div wire:key="img-{{ $image->id }}" class="break-inside-avoid group relative rounded-lg overflow-hidden bg-slate-800 hover:ring-2 hover:ring-indigo-500 transition-all">
                <a href="{{ route('gallery.show', $image) }}" class="block">
                    <img src="{{ Storage::disk(config('filesystems.default'))->url($image->thumbnail_path ?? $image->file_path) }}"
                         alt="{{ Str::limit($image->prompt->prompt_text ?? '', 50) }}"
                         class="w-full object-cover group-hover:opacity-90 transition-opacity"
                         loading="lazy">
                </a>
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                    <p class="text-xs text-white line-clamp-2 mb-2">{{ Str::limit($image->prompt->prompt_text ?? '', 80) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] uppercase tracking-wide text-slate-400">{{ $image->provider }}</span>
                        <div class="flex gap-1">
                            <button wire:click="toggleFavorite({{ $image->id }})"
                                    class="p-1 rounded hover:bg-white/10 transition">
                                <svg class="w-4 h-4 {{ $image->is_favorite ? 'fill-amber-400 text-amber-400' : 'text-white' }}" fill="{{ $image->is_favorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @if($image->is_favorite)
                <div class="absolute top-2 right-2 text-amber-400 pointer-events-none">
                    <svg class="w-4 h-4 fill-current drop-shadow" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $images->links() }}</div>
    @endif
</div>