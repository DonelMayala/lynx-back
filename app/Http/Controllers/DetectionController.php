<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDetectionStatusRequest;
use App\Models\Detection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DetectionController extends Controller
{
    public function index(): View
    {
        $detections = Detection::with(['camera.site', 'recording', 'validator'])->latest('detected_at')->paginate(15);

        return view('detections.index', compact('detections'));
    }

    public function update(UpdateDetectionStatusRequest $request, Detection $detection): RedirectResponse
    {
        $detection->update([
            'validation_status' => $request->validated('validation_status'),
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        return back()->with('status', 'Détection examinée.');
    }
}
