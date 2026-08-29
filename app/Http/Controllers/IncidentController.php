<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\View\View;

class IncidentController extends Controller
{
    public function index(): View
    {
        $incidents = Incident::with(['assignee', 'alert.detection.camera'])->latest('opened_at')->paginate(15);

        return view('incidents.index', compact('incidents'));
    }

    public function show(Incident $incident): View
    {
        $incident->load(['assignee', 'alert.detection.camera.site', 'events.user']);

        return view('incidents.show', compact('incident'));
    }
}
