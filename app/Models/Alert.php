<?php

namespace App\Models;

use Database\Factories\AlertFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['alert_rule_id', 'detection_id', 'severity', 'status', 'latitude', 'longitude', 'triggered_at', 'acknowledged_at', 'resolved_at', 'resolution_note'])]
class Alert extends Model
{
    /** @use HasFactory<AlertFactory> */
    use HasFactory, HasUuids;

    public function detection(): BelongsTo
    {
        return $this->belongsTo(Detection::class);
    }

    public function incident(): HasOne
    {
        return $this->hasOne(Incident::class);
    }

    protected function casts(): array
    {
        return [
            'triggered_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }
}
