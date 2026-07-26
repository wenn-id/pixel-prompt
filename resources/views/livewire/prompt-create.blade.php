<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">New Prompt</h1>
        <p class="text-sm text-slate-400 mt-1">Compose your prompt and generate an image</p>
    </div>

    <form wire:submit="generate" class="space-y-6">
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Title <span class="text-slate-500">(optional)</span></label>
                        <input wire:model="title" type="text" placeholder="Give your prompt a name..."
                               class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Prompt <span class="text-red-400">*</span></label>
                        <textarea wire:model="prompt_text" rows="5" placeholder="Describe the image you want to generate..."
                                  class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500 resize-y">{{ $prompt_text }}</textarea>
                        @error('prompt_text') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Negative Prompt</label>
                        <textarea wire:model="negative_prompt" rows="2" placeholder="What to avoid in the image..."
                                  class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500 resize-y"></textarea>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                    <h3 class="text-sm font-medium text-slate-300 mb-3">Parameters</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Width</label>
                            <select wire:model="width" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                                <option value="512">512</option>
                                <option value="768">768</option>
                                <option value="1024">1024</option>
                                <option value="1344">1344</option>
                                <option value="1536">1536</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Height</label>
                            <select wire:model="height" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                                <option value="512">512</option>
                                <option value="768">768</option>
                                <option value="1024">1024</option>
                                <option value="1344">1344</option>
                                <option value="1536">1536</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">CFG Scale</label>
                            <input wire:model="cfg_scale" type="number" step="0.5" min="1" max="20"
                                   class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Steps</label>
                            <input wire:model="steps" type="number" min="1" max="150"
                                   class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Seed <span class="text-slate-600">(leave empty for random)</span></label>
                            <input wire:model="seed" type="number" placeholder="Random"
                                   class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Style Preset</label>
                            <input wire:model="style_preset" type="text" placeholder="e.g. cinematic, anime..."
                                   class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
                    <h3 class="text-sm font-medium text-slate-300">Provider & Model</h3>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Provider <span class="text-red-400">*</span></label>
                        <select wire:model.live="provider" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select provider</option>
                            @foreach($keys as $provider => $k)
                            <option value="{{ $provider }}">{{ ucfirst($provider) }}</option>
                            @endforeach
                        </select>
                        @error('provider') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        @if($keys->isEmpty())
                        <p class="text-xs text-amber-400 mt-2">No API keys configured.
                            <a href="{{ route('settings.keys') }}" class="underline">Add one</a>
                        </p>
                        @endif
                    </div>

                    @if($provider && isset($available_models[$provider]))
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Model <span class="text-red-400">*</span></label>
                        <select wire:model="model" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select model</option>
                            @foreach($available_models[$provider] as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                        @error('model') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Tags <span class="text-slate-600">(comma separated)</span></label>
                        <input wire:model="tags_input" type="text" placeholder="fantasy, cinematic, landscape..."
                               class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                        @class([
                            'w-full px-5 py-3 rounded-xl font-medium text-sm transition-all',
                            'bg-indigo-600 hover:bg-indigo-500 text-white' => !$is_generating,
                            'bg-indigo-800 text-indigo-300 cursor-wait' => $is_generating,
                        ])>
                    <span wire:loading.remove>{{ $is_generating ? 'Generating...' : 'Generate' }}</span>
                    <span wire:loading>
                        <svg class="w-4 h-4 animate-spin mx-auto inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Generating...
                    </span>
                </button>

                @if($generation_error)
                <div class="bg-red-900/30 border border-red-800 rounded-lg p-3 text-sm text-red-300">
                    {{ $generation_error }}
                </div>
                @endif
            </div>
        </div>
    </form>
</div>