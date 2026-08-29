<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        Gate::authorize('administer-system');

        $sites = Site::withCount('cameras')
            ->when(auth()->user()->organization_id, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->orderByDesc('active')
            ->orderBy('name')
            ->paginate(12);

        return view('sites.index', compact('sites'));
    }

    public function store(StoreSiteRequest $request): RedirectResponse
    {
        $site = DB::transaction(function () use ($request): Site {
            $site = Site::create([
                ...$request->validated(),
                'organization_id' => $request->user()->organization_id,
            ]);

            DB::table('audit_logs')->insert([
                'id' => (string) Str::uuid(),
                'organization_id' => $site->organization_id,
                'user_id' => $request->user()->id,
                'action' => 'site.created',
                'resource_type' => Site::class,
                'resource_id' => $site->id,
                'ip_address' => $request->ip(),
                'metadata' => json_encode(['name' => $site->name], JSON_THROW_ON_ERROR),
                'created_at' => now(),
            ]);

            return $site;
        });

        return redirect()->route('sites.index')->with('status', "Le site {$site->name} a été ajouté.");
    }
}
