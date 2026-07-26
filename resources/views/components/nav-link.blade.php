@props(['active' => false, 'href' => '#'])
<a href="{{ $href }}" @class([
    'px-3 py-1.5 rounded-md text-sm font-medium transition',
    'bg-indigo-600/20 text-indigo-300' => $active,
    'text-slate-400 hover:text-white hover:bg-slate-800' => !$active,
])>{{ $slot }}</a>