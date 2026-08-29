<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Camera;
use App\Models\Detection;
use App\Models\Incident;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $stats = [
            'active_alerts' => Alert::whereNotIn('status', ['resolved', 'false_positive'])->count(),
            'online_cameras' => Camera::where('status', 'online')->count(),
            'pending_detections' => Detection::where('validation_status', 'pending')->count(),
            'open_incidents' => Incident::whereNotIn('status', ['closed', 'resolved'])->count(),
        ];
        $recentAlerts = Alert::with(['detection.camera.site', 'incident'])->latest('triggered_at')->limit(6)->get();
        $cameras = Camera::with(['site', 'latestRecording'])->latest('last_seen_at')->limit(8)->get();

        return view('dashboard', compact('stats', 'recentAlerts', 'cameras'));
    }
}
