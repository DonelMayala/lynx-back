<?php

namespace App\Models;

use Database\Factories\IncidentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['alert_id', 'reference_number', 'title', 'description', 'priority', 'status', 'assigned_to', 'opened_at', 'closed_at'])]
class Incident extends Model
{
    /** @use HasFactory<IncidentFactory> */
    use HasFactory, HasUuids;

    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function events(): HasMany
    {
        return $this->hasMany(IncidentEvent::class);
    }

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }
}
