<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Centre de contrôle') — Lynx Vision</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div data-sidebar-backdrop data-sidebar-toggle class="fixed inset-0 z-30 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>
    <aside data-sidebar class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-ink-950 text-white transition-transform lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <div class="grid size-10 place-items-center rounded-xl bg-lynx-500 text-ink-950">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11.5 12 4l9 7.5v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M8 14h8M9 17h6"/></svg>
            </div>
            <div><p class="font-semibold">Lynx Vision</p><p class="text-xs text-slate-400">Centre de contrôle</p></div>
        </div>
        <nav class="flex-1 space-y-1 overflow-y-auto p-4">
            @php
                $links = [
                    ['dashboard', 'Vue générale', 'M4 13h6V4H4v9Zm0 7h6v-5H4v5Zm10 0h6v-9h-6v9Zm0-16v5h6V4h-6Z'],
                    ['alerts.index', 'Alertes', 'M12 9v4m0 4h.01M10.3 3.7 2.6 17a2 2 0 0 0 1.73 3h15.34A2 2 0 0 0 21.4 17L13.7 3.7a2 2 0 0 0-3.4 0Z'],
                    ['cameras.index', 'Carte & caméras', 'M3 7h13v10H3zM16 10l5-3v10l-5-3'],
                    ['detections.index', 'Détections', 'M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-7 16a7 7 0 0 1 14 0'],
                    ['incidents.index', 'Incidents', 'M6 3h12v18H6zM9 7h6M9 11h6M9 15h4'],
                    ['admin.index', 'Administration', 'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2 3.46-.09-.03a1.7 1.7 0 0 0-1.8.23l-.08.06a1.7 1.7 0 0 0-.66 1.76V22h-4v-.1a1.7 1.7 0 0 0-.66-1.76l-.08-.06a1.7 1.7 0 0 0-1.8-.23l-.09.03-2-3.46.06-.06A1.7 1.7 0 0 0 6.6 15l-.02-.1a1.7 1.7 0 0 0-1.13-1.43L5.36 13v-4l.09-.03A1.7 1.7 0 0 0 6.58 7.5l.02-.1a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2-3.46.09.03a1.7 1.7 0 0 0 1.8-.23l.08-.06A1.7 1.7 0 0 0 10.83 0h4a1.7 1.7 0 0 0 .66 1.74l.08.06a1.7 1.7 0 0 0 1.8.23l.09-.03 2 3.46-.06.06a1.7 1.7 0 0 0-.34 1.88l.02.1a1.7 1.7 0 0 0 1.13 1.43l.09.03v4l-.09.03a1.7 1.7 0 0 0-1.13 1.43Z'],
                ];
            @endphp
            @foreach ($links as [$routeName, $label, $icon])
                @continue($routeName === 'admin.index' && ! auth()->user()->can('administer-system'))
                <a href="{{ route($routeName) }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs($routeName) ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
        <div class="border-t border-white/10 p-4">
            <div class="mb-3 flex items-center gap-3 px-2">
                <div class="grid size-9 place-items-center rounded-full bg-lynx-500/20 text-sm font-bold text-lynx-100">{{ str(auth()->user()->name)->substr(0, 2)->upper() }}</div>
                <div class="min-w-0"><p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p><p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p></div>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full rounded-xl border border-white/10 px-3 py-2 text-sm text-slate-300 hover:bg-white/5">Se déconnecter</button></form>
        </div>
    </aside>
    <div class="lg:pl-72">
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-8">
            <div class="flex items-center gap-3"><button data-sidebar-toggle class="grid size-10 place-items-center rounded-xl border border-slate-200 lg:hidden" aria-label="Ouvrir le menu"><svg class="size-5" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button><div><p class="eyebrow">Lynx Vision</p><h1 class="text-lg font-semibold">@yield('heading', 'Centre de contrôle')</h1></div></div>
            <div class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 sm:flex"><span class="size-2 rounded-full bg-emerald-500"></span>Système opérationnel</div>
        </header>
        <main class="p-4 sm:p-8">
            @if (session('status'))<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
