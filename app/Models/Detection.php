<?php

namespace App\Models;

use Database\Factories\DetectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['camera_id', 'recording_id', 'detection_type', 'confidence', 'detected_at', 'bounding_box', 'snapshot_key', 'model_metadata', 'validation_status', 'validated_by', 'validated_at'])]
class Detection extends Model
{
    /** @use HasFactory<DetectionFactory> */
    use HasFactory, HasUuids;

    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }

    public function recording(): BelongsTo
    {
        return $this->belongsTo(VideoRecording::class, 'recording_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    protected function casts(): array
    {
        return [
            'bounding_box' => 'array',
            'model_metadata' => 'array',
            'detected_at' => 'datetime',
            'validated_at' => 'datetime',
        ];
    }
}
