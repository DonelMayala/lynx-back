<?php

namespace App\Models;

use Database\Factories\VideoRecordingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['camera_id', 'storage_key', 'started_at', 'ended_at', 'duration_seconds', 'size_bytes', 'checksum', 'retention_until', 'status'])]
class VideoRecording extends Model
{
    /** @use HasFactory<VideoRecordingFactory> */
    use HasFactory, HasUuids;

    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }

    public function detections(): HasMany
    {
        return $this->hasMany(Detection::class, 'recording_id');
    }

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'retention_until' => 'datetime',
        ];
    }
}
