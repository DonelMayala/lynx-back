<!DOCTYPE html>
<html lang="fr">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Connexion — Lynx Vision</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="grid min-h-screen lg:grid-cols-2">
    <section class="relative hidden overflow-hidden bg-ink-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
        <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle at 1px 1px,#2dd4bf 1px,transparent 0);background-size:28px 28px"></div>
        <div class="relative flex items-center gap-3"><div class="grid size-11 place-items-center rounded-xl bg-lynx-500 text-ink-950"><svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11.5 12 4l9 7.5v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div><div><p class="text-lg font-bold">Lynx Vision</p><p class="text-sm text-slate-400">Intelligence urbaine</p></div></div>
        <div class="relative max-w-xl"><p class="mb-5 text-sm font-semibold tracking-[0.2em] text-lynx-500 uppercase">Centre de commandement</p><h1 class="text-5xl font-semibold leading-tight">Voir plus tôt.<br>Décider plus vite.</h1><p class="mt-6 max-w-md text-lg leading-8 text-slate-400">Une vue unifiée des caméras, alertes et incidents pour coordonner les opérations en temps réel.</p></div>
        <p class="relative text-sm text-slate-500">Accès réservé au personnel autorisé</p>
    </section>
    <section class="flex items-center justify-center bg-white p-6 sm:p-12">
        <div class="w-full max-w-md">
            <div class="mb-10 lg:hidden"><p class="text-2xl font-bold">Lynx Vision</p><p class="text-sm text-slate-500">Centre de contrôle</p></div>
            <p class="eyebrow">Connexion sécurisée</p><h2 class="mt-2 text-3xl font-semibold tracking-tight">Bienvenue</h2><p class="mt-3 text-slate-500">Utilisez votre compte opérationnel.</p>
            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">@csrf
                <label class="block"><span class="mb-2 block text-sm font-medium">Adresse e-mail</span><input name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-lynx-500 focus:ring-3 focus:ring-lynx-100"></label>
                <label class="block"><span class="mb-2 block text-sm font-medium">Mot de passe</span><input name="password" type="password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-lynx-500 focus:ring-3 focus:ring-lynx-100"></label>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input name="remember" type="checkbox" value="1" class="rounded border-slate-300 text-lynx-600">Maintenir la session ouverte</label>
                @error('email')<p class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">{{ $message }}</p>@enderror
                <button class="btn-primary w-full py-3">Accéder au centre de contrôle</button>
            </form>
        </div>
    </section>
</body>
</html>
