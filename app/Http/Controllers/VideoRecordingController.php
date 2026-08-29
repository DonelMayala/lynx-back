<?php

namespace App\Http\Controllers;

use App\Models\VideoRecording;
use Illuminate\View\View;

class VideoRecordingController extends Controller
{
    public function show(VideoRecording $recording): View
    {
        $recording->load(['camera.site', 'detections']);

        return view('recordings.show', compact('recording'));
    }
}
