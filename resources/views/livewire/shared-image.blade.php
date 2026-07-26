<div class="max-w-4xl mx-auto">
    <div class="rounded-xl overflow-hidden bg-slate-900 border border-slate-800 mb-4">
        <img src="{{ Storage::disk(config('filesystems.default'))->url($image->file_path) }}"
             alt="Shared image" class="w-full object-contain max-h-[80vh]">
    </div>
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                        {{ substr($image->user->name, 0, 1) }}
                    </div>
                    <span class="text-sm text-slate-300">{{ $image->user->name }}</span>
                </div>
                @if($image->prompt)
                <p class="text-sm text-slate-200">{{ $image->prompt->prompt_text }}</p>
                @endif
                <div class="flex gap-3 mt-3 text-xs text-slate-500">
                    <span>{{ $image->provider }} / {{ $image->model }}</span>
                    <span>{{ $image->width }}x{{ $image->height }}</span>
                    <span>{{ $image->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>