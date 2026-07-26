<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Your creative workspace at a glance</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Images', 'value' => $stats['total_images'], 'color' => 'indigo'],
            ['label' => 'Prompts', 'value' => $stats['total_prompts'], 'color' => 'violet'],
            ['label' => 'Collections', 'value' => $stats['total_collections'], 'color' => 'blue'],
            ['label' => 'Favorites', 'value' => $stats['favorites'], 'color' => 'amber'],
        ] as $stat)
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 hover:border-slate-700 transition group">
            <div class="text-3xl font-bold text-white tabular-nums">{{ $stat['value'] }}</div>
            <div class="text-sm text-slate-400 mt-1">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-white">Recent Images</h2>
        <a href="{{ route('gallery.index') }}" class="text-sm text-indigo-400 hover:text-indigo-300">View all →</a>
    </div>

    @if($recent->isEmpty())
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            <p class="text-slate-400 text-sm">No images yet. Start by creating your first prompt.</p>
            <a href="{{ route('prompts.create') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
                Create Prompt
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            @foreach($recent as $image)
            <a href="{{ route('gallery.show', $image) }}" class="group relative aspect-square rounded-lg overflow-hidden bg-slate-800 hover:ring-2 hover:ring-indigo-500 transition-all">
                <img src="{{ Storage::disk(config('filesystems.default'))->url($image->thumbnail_path ?? $image->file_path) }}"
                     alt="{{ Str::limit($image->prompt->prompt_text ?? '', 50) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="absolute bottom-0 p-2 text-xs text-white line-clamp-2">
                        {{ Str::limit($image->prompt->prompt_text ?? '', 60) }}
                    </div>
                </div>
                @if($image->is_favorite)
                <div class="absolute top-2 right-2 text-amber-400">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    @endif
</div>