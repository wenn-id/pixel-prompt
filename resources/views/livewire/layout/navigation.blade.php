<nav class="bg-slate-900/80 border-b border-slate-800 backdrop-blur sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight">
                    <span class="text-indigo-400">Pixel</span>Prompt
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <x-nav-link href="{{ route('gallery.index') }}" :active="request()->routeIs('gallery.*')">Gallery</x-nav-link>
                    <x-nav-link href="{{ route('prompts.create') }}" :active="request()->routeIs('prompts.create')">New Prompt</x-nav-link>
                    <x-nav-link href="{{ route('prompts.index') }}" :active="request()->routeIs('prompts.index')">Prompts</x-nav-link>
                    <x-nav-link href="{{ route('collections.index') }}" :active="request()->routeIs('collections.*')">Collections</x-nav-link>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('settings.keys') }}" class="text-sm text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                </a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm text-slate-300 hover:text-white transition">
                        <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-medium">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-lg shadow-xl py-1 z-50">
                        <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">Settings</a>
                        <a href="{{ route('settings.keys') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">API Keys</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-slate-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>