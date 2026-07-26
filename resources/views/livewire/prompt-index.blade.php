<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Prompts</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $prompts->total() }} prompts saved</p>
        </div>
        <a href="{{ route('prompts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
            New Prompt
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6">
        <div class="flex gap-3">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search prompts..."
                   class="flex-1 bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
            <select wire:model.live="provider" class="bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                <option value="">All providers</option>
                @foreach($providers as $p)
                <option value="{{ $p }}">{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($prompts->isEmpty())
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-12 text-center text-slate-400 text-sm">
        No prompts yet.
    </div>
    @else
    <div class="space-y-2">
        @foreach($prompts as $prompt)
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 hover:border-slate-700 transition group">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('prompts.edit', $prompt) }}" class="text-white font-medium hover:text-indigo-400 transition line-clamp-1">
                        {{ $prompt->title ?: 'Untitled' }}
                    </a>
                    <p class="text-sm text-slate-400 mt-0.5 line-clamp-2">{{ $prompt->prompt_text }}</p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                        <span>{{ $prompt->provider }} / {{ $prompt->model }}</span>
                        <span>{{ $prompt->images_count }} images</span>
                        <span>{{ $prompt->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <button wire:click="deletePrompt({{ $prompt->id }})" wire:confirm="Delete this prompt?"
                        class="p-2 opacity-0 group-hover:opacity-100 text-red-400 hover:bg-red-900/30 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $prompts->links() }}</div>
    @endif
</div>