<?php

namespace App\Models;

use Database\Factories\SiteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['organization_id', 'name', 'address', 'timezone', 'active', 'latitude', 'longitude'])]
class Site extends Model
{
    /** @use HasFactory<SiteFactory> */
    use HasFactory, HasUuids;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function cameras(): HasMany
    {
        return $this->hasMany(Camera::class);
    }

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }
}
