<div>
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center text-2xl font-bold text-white mx-auto mb-3">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
            @if($user->bio)
            <p class="text-slate-400 mt-1">{{ $user->bio }}</p>
            @endif
            <p class="text-sm text-slate-500 mt-1">{{ $images->total() }} public images</p>
        </div>

        @if($images->isEmpty())
        <div class="bg-slate-900 rounded-xl p-12 text-center text-slate-400 text-sm">
            No public images yet.
        </div>
        @else
        <div class="columns-2 sm:columns-3 lg:columns-4 gap-3 space-y-3">
            @foreach($images as $image)
            <div class="break-inside-avoid rounded-lg overflow-hidden bg-slate-900">
                <img src="{{ Storage::disk(config('filesystems.default'))->url($image->thumbnail_path ?? $image->file_path) }}"
                     alt="" class="w-full object-cover" loading="lazy">
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $images->links() }}</div>
        @endif
    </div>
</div>