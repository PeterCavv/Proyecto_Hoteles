<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'type',
        'description',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope a query to apply filter conditions based on the provided filters.
     *
     * @param Builder $query The query builder instance.
     * @param array $filters The filters to be applied (e.g., city, name, type).
     *
     * @return Builder The modified query builder instance with applied filters.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['city'])) {
            $query->whereHas('city', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['city'] . '%');
            });
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query;
    }
}

