<?php

namespace App\Models;

use Database\Factories\CameraFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['site_id', 'name', 'code', 'stream_url_encrypted', 'protocol', 'latitude', 'longitude', 'direction_degrees', 'status', 'capabilities', 'last_seen_at'])]
#[Hidden(['stream_url_encrypted'])]
class Camera extends Model
{
    /** @use HasFactory<CameraFactory> */
    use HasFactory, HasUuids;

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(VideoRecording::class);
    }

    public function latestRecording(): HasOne
    {
        return $this->hasOne(VideoRecording::class)->latestOfMany('started_at');
    }

    public function detections(): HasMany
    {
        return $this->hasMany(Detection::class);
    }

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
            'stream_url_encrypted' => 'encrypted',
            'last_seen_at' => 'datetime',
        ];
    }
}
