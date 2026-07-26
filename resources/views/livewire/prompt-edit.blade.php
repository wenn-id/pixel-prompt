<div>
    <div class="mb-6">
        <a href="{{ route('prompts.index') }}" class="text-sm text-slate-400 hover:text-white transition">&larr; Back</a>
        <h1 class="text-2xl font-bold text-white mt-2">Edit Prompt</h1>
    </div>

    <form wire:submit="save" class="space-y-4 max-w-2xl">
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Title</label>
                <input wire:model="title" type="text" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Prompt</label>
                <textarea wire:model="prompt_text" rows="5" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 resize-y"></textarea>
                @error('prompt_text') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Negative Prompt</label>
                <textarea wire:model="negative_prompt" rows="2" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 resize-y"></textarea>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Width</label>
                    <input wire:model="width" type="number" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Height</label>
                    <input wire:model="height" type="number" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">CFG Scale</label>
                    <input wire:model="cfg_scale" type="number" step="0.5" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Tags (comma separated)</label>
                <input wire:model="tags_input" type="text" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
            </div>
        </div>

        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
            Save Changes
        </button>
    </form>
</div>