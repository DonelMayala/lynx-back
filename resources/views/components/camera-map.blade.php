@props(['cameras'])
@php
    $cameraData = $cameras
        ->filter(fn ($camera) => $camera->latitude !== null && $camera->longitude !== null)
        ->map(function ($camera) {
            $storageKey = $camera->latestRecording?->storage_key;
            $previewUrl = is_string($storageKey) && str($storageKey)->startsWith(['https://', 'http://', '/'])
                ? $storageKey
                : null;
            $isDemoVideo = $previewUrl === null;
            $previewUrl ??= config('surveillance.demo_video_url');

            return [
                'id' => $camera->id,
                'name' => $camera->name,
                'code' => $camera->code,
                'site' => $camera->site?->name ?? 'Site inconnu',
                'status' => $camera->status,
                'latitude' => (float) $camera->latitude,
                'longitude' => (float) $camera->longitude,
                'last_seen' => $camera->last_seen_at?->diffForHumans() ?? 'Jamais',
                'preview_url' => $previewUrl,
                'preview_is_demo' => $isDemoVideo,
            ];
        })
        ->values();
@endphp
<div data-map-wrapper class="relative overflow-hidden rounded-2xl bg-slate-100">
    <div data-camera-map data-cameras="{{ $cameraData->toJson() }}" class="h-[420px] w-full sm:h-[500px]" aria-label="Carte interactive des caméras"></div>
    <div class="pointer-events-none absolute left-4 top-4 z-[500] flex flex-col gap-2">
        <div class="pointer-events-auto rounded-xl bg-white/95 p-3 shadow-lg backdrop-blur">
            <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Réseau de caméras</p>
            <p class="mt-1 text-sm font-semibold"><span data-camera-count>{{ $cameraData->count() }}</span> géolocalisée{{ $cameraData->count() > 1 ? 's' : '' }}</p>
        </div>
        <div class="pointer-events-auto flex w-fit gap-1 rounded-xl bg-white/95 p-1.5 shadow-lg backdrop-blur">
            <button type="button" data-map-reset class="rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100" title="Recentrer sur les caméras">Recentrer</button>
            <button type="button" data-map-fullscreen class="rounded-lg px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100" title="Afficher la carte en plein écran">Plein écran</button>
        </div>
    </div>
    @if($cameraData->isEmpty())
        <div class="pointer-events-none absolute inset-0 z-[450] grid place-items-center"><div class="rounded-2xl bg-white/95 px-6 py-5 text-center shadow-lg"><p class="font-semibold">Aucune caméra géolocalisée</p><p class="mt-1 text-sm text-slate-500">Ajoutez des coordonnées aux caméras.</p></div></div>
    @endif
    <div class="pointer-events-none absolute bottom-5 left-5 z-[500] flex flex-wrap gap-3 rounded-xl bg-white/95 px-3 py-2 text-xs font-medium shadow-lg backdrop-blur"><span class="flex items-center gap-1.5"><i class="size-2.5 rounded-full bg-emerald-500"></i>En ligne</span><span class="flex items-center gap-1.5"><i class="size-2.5 rounded-full bg-amber-500"></i>Maintenance</span><span class="flex items-center gap-1.5"><i class="size-2.5 rounded-full bg-slate-500"></i>Hors ligne</span></div>
</div>
