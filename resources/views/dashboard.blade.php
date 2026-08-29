@extends('layouts.app')
@section('title', 'Vue générale')
@section('heading', 'Vue générale')
@section('content')
<div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end"><div><p class="eyebrow">Situation en temps réel</p><h2 class="mt-2 text-3xl font-semibold tracking-tight">Centre de commandement</h2><p class="mt-2 text-slate-500">Vue synthétique des opérations de surveillance.</p></div><p class="text-sm text-slate-500">Actualisé à {{ now()->format('H:i') }}</p></div>
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([['Alertes actives', $stats['active_alerts'], 'À traiter', 'text-red-700 bg-red-50'], ['Caméras en ligne', $stats['online_cameras'], 'Opérationnelles', 'text-emerald-700 bg-emerald-50'], ['Détections à valider', $stats['pending_detections'], 'File de contrôle', 'text-amber-700 bg-amber-50'], ['Incidents ouverts', $stats['open_incidents'], 'En cours', 'text-blue-700 bg-blue-50']] as [$label, $value, $note, $color])
        <article class="panel p-5"><div class="flex items-start justify-between"><div><p class="text-sm font-medium text-slate-500">{{ $label }}</p><p class="mt-3 text-3xl font-semibold">{{ $value }}</p></div><span class="rounded-lg px-2.5 py-1 text-xs font-semibold {{ $color }}">{{ $note }}</span></div></article>
    @endforeach
</div>
<div class="mt-6 grid gap-6 xl:grid-cols-[1.3fr_.7fr]">
    <section class="panel p-4 sm:p-6"><div class="mb-5 flex items-center justify-between"><div><p class="eyebrow">Couverture</p><h3 class="mt-1 text-lg font-semibold">Carte des caméras</h3></div><a href="{{ route('cameras.index') }}" class="text-sm font-semibold text-lynx-700">Ouvrir la carte →</a></div><x-camera-map :cameras="$cameras" /></section>
    <section class="panel overflow-hidden"><div class="flex items-center justify-between border-b border-slate-100 p-5"><div><p class="eyebrow">Priorités</p><h3 class="mt-1 text-lg font-semibold">Alertes récentes</h3></div><a href="{{ route('alerts.index') }}" class="text-sm font-semibold text-lynx-700">Tout voir</a></div>
        <div class="divide-y divide-slate-100">@forelse($recentAlerts as $alert)<div class="flex gap-3 p-4"><span class="mt-1 size-2.5 shrink-0 rounded-full {{ $alert->severity === 'critical' ? 'bg-red-500' : ($alert->severity === 'high' ? 'bg-orange-500' : 'bg-amber-400') }}"></span><div class="min-w-0 flex-1"><div class="flex justify-between gap-2"><p class="truncate text-sm font-semibold">{{ str($alert->detection?->detection_type ?? 'Alerte système')->headline() }}</p><time class="shrink-0 text-xs text-slate-400">{{ $alert->triggered_at?->diffForHumans() }}</time></div><p class="mt-1 truncate text-xs text-slate-500">{{ $alert->detection?->camera?->name ?? 'Source inconnue' }} · {{ $alert->detection?->camera?->site?->name }}</p></div></div>@empty<div class="p-8 text-center text-sm text-slate-500">Aucune alerte récente.</div>@endforelse</div>
    </section>
</div>
@endsection
