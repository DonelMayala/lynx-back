<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAlertStatusRequest;
use App\Models\Alert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlertController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $allowedStatuses = ['new', 'acknowledged', 'investigating', 'resolved', 'false_positive'];
        $alerts = Alert::with(['detection.camera.site', 'incident'])
            ->when(in_array($status, $allowedStatuses, true), fn ($query) => $query->where('status', $status))
            ->latest('triggered_at')->paginate(15)->withQueryString();

        return view('alerts.index', compact('alerts', 'status'));
    }

    public function update(UpdateAlertStatusRequest $request, Alert $alert): RedirectResponse
    {
        $status = $request->validated('status');
        $alert->update([
            'status' => $status,
            'acknowledged_at' => $status === 'acknowledged' ? now() : $alert->acknowledged_at,
            'resolved_at' => in_array($status, ['resolved', 'false_positive'], true) ? now() : null,
            'resolution_note' => $request->validated('resolution_note'),
        ]);

        return back()->with('status', 'Alerte mise à jour.');
    }
}
