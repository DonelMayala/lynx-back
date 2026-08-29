<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCameraRequest;
use App\Models\Camera;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CameraController extends Controller
{
    public function index(): View
    {
        $cameras = Camera::with(['site', 'latestRecording'])->orderBy('name')->get();

        return view('cameras.index', compact('cameras'));
    }

    public function create(): View
    {
        Gate::authorize('administer-system');

        $sites = Site::query()
            ->when(auth()->user()->organization_id, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'address']);

        return view('cameras.create', compact('sites'));
    }

    public function store(StoreCameraRequest $request): RedirectResponse
    {
        $camera = DB::transaction(function () use ($request): Camera {
            $data = $request->safe()->except(['stream_url']);
            $data['stream_url_encrypted'] = $request->validated('stream_url');

            $camera = Camera::create($data);
            $site = Site::findOrFail($camera->site_id);

            DB::table('audit_logs')->insert([
                'id' => (string) Str::uuid(),
                'organization_id' => $site->organization_id,
                'user_id' => $request->user()->id,
                'action' => 'camera.created',
                'resource_type' => Camera::class,
                'resource_id' => $camera->id,
                'ip_address' => $request->ip(),
                'metadata' => json_encode(['code' => $camera->code], JSON_THROW_ON_ERROR),
                'created_at' => now(),
            ]);

            return $camera;
        });

        return redirect()->route('cameras.index')->with('status', "La caméra {$camera->name} a été ajoutée.");
    }
}
