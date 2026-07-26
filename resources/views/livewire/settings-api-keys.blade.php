<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">API Keys</h1>
        <p class="text-sm text-slate-400 mt-1">Connect your AI image generation providers</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <h3 class="text-sm font-medium text-slate-300 mb-4">Add Key</h3>
                <form wire:submit="add" class="space-y-4">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Provider</label>
                        <select wire:model="provider" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            @foreach($providers as $p)
                            <option value="{{ $p }}">{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                        @error('provider') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Label</label>
                        <input wire:model="label" type="text" placeholder="My OpenAI key"
                               class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                        @error('label') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">API Key</label>
                        <input wire:model="key" type="password" placeholder="sk-..."
                               class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                        @error('key') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
                        Add Key
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($providers as $providerName)
                @php $providerKeys = $keys->where('provider', $providerName); @endphp
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                    <h4 class="text-sm font-medium text-white capitalize mb-2">{{ $providerName }}</h4>
                    @if($providerKeys->isEmpty())
                    <p class="text-xs text-slate-500">No keys configured</p>
                    @else
                    <div class="space-y-2">
                        @foreach($providerKeys as $k)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/50">
                            <div>
                                <span class="text-sm text-slate-200">{{ $k->label }}</span>
                                <span class="text-xs text-slate-500 ml-2">••••••{{ substr($k->key_encrypted, -8) }}</span>
                            </div>
                            <div class="flex gap-2">
                                @if($confirm_delete_id === $k->id)
                                <span class="text-xs text-red-400">Sure?</span>
                                <button wire:click="delete({{ $k->id }})" class="text-xs text-red-400 hover:text-red-300">Yes</button>
                                <button wire:click="cancelDelete" class="text-xs text-slate-400 hover:text-white">No</button>
                                @else
                                <button wire:click="confirmDelete({{ $k->id }})" class="text-xs text-red-400 hover:text-red-300">Remove</button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>