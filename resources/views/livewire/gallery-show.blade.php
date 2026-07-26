<div>
    <div class="mb-6">
        <a href="{{ route('gallery.index') }}" class="text-sm text-slate-400 hover:text-white transition">&larr; Back to gallery</a>
    </div>

    <div class="grid lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3">
            <div class="rounded-xl overflow-hidden bg-slate-900 border border-slate-800">
                <img src="{{ Storage::disk(config('filesystems.default'))->url($image->file_path) }}"
                     alt="Generated image"
                     class="w-full object-contain max-h-[70vh]">
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white">Details</h2>
                    <div class="flex gap-2">
                        <button wire:click="toggleFavorite" @class([
                            'p-2 rounded-lg transition',
                            'bg-amber-500/20 text-amber-400' => $image->is_favorite,
                            'bg-slate-800 text-slate-400 hover:text-white' => !$image->is_favorite,
                        ])>
                            <svg class="w-5 h-5" fill="{{ $image->is_favorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        </button>
                        <button wire:click="togglePublic" @class([
                            'p-2 rounded-lg transition',
                            'bg-blue-500/20 text-blue-400' => $image->is_public,
                            'bg-slate-800 text-slate-400 hover:text-white' => !$image->is_public,
                        ])>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    @if($image->prompt)
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-500 font-medium">Prompt</label>
                        <p class="text-slate-200 mt-0.5">{{ $image->prompt->prompt_text }}</p>
                    </div>
                    @endif

                    @if($image->prompt?->negative_prompt)
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-500 font-medium">Negative Prompt</label>
                        <p class="text-slate-300 mt-0.5">{{ $image->prompt->negative_prompt }}</p>
                    </div>
                    @endif

                    @if($image->tags->isNotEmpty())
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-500 font-medium">Tags</label>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @foreach($image->tags as $tag)
                            <span class="px-2 py-0.5 bg-slate-800 text-slate-300 rounded-full text-xs">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-800">
                        <div><label class="text-xs text-slate-500">Provider</label><p class="text-slate-200">{{ $image->provider ?? '-' }}</p></div>
                        <div><label class="text-xs text-slate-500">Model</label><p class="text-slate-200">{{ $image->model ?? '-' }}</p></div>
                        <div><label class="text-xs text-slate-500">Size</label><p class="text-slate-200">{{ $image->width }}x{{ $image->height }}</p></div>
                        <div><label class="text-xs text-slate-500">Created</label><p class="text-slate-200">{{ $image->created_at->format('M j, Y g:i A') }}</p></div>
                        @if($image->generation_time_ms)
                        <div><label class="text-xs text-slate-500">Gen Time</label><p class="text-slate-200">{{ number_format($image->generation_time_ms / 1000, 1) }}s</p></div>
                        @endif
                    </div>
                </div>
            </div>

            @if($image->is_public && $image->share_token)
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <label class="text-xs uppercase tracking-wide text-slate-500 font-medium block mb-2">Share Link</label>
                <div class="flex gap-2">
                    <input type="text" readonly value="{{ $image->share_url }}" class="flex-1 bg-slate-800 border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-300">
                    <button onclick="navigator.clipboard.writeText('{{ $image->share_url }}'); this.textContent='Copied!'" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-sm text-white rounded-lg transition">Copy</button>
                </div>
            </div>
            @endif

            <button wire:click="delete" wire:confirm="Delete this image?" class="w-full px-4 py-2 bg-red-900/30 hover:bg-red-900/50 text-red-400 text-sm rounded-lg transition">
                Delete Image
            </button>
        </div>
    </div>
</div>