<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PixelPrompt') }} @isset($title) - {{ $title }} @endisset</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100">
    <div class="min-h-screen">
        <livewire:layout.navigation />
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('status') === 'generation-queued')
                    <div class="mb-4 p-4 rounded-lg bg-emerald-900/40 border border-emerald-700 text-emerald-200 text-sm">
                        Generation queued! Your image will appear in the gallery once complete.
                    </div>
                @endif
                @if (session('status'))
                    <div class="mb-4 p-4 rounded-lg bg-indigo-900/40 border border-indigo-700 text-indigo-200 text-sm">
                        {{ session('status') }}
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>
    @stack('modals')
    @livewireScripts
</body>
</html>