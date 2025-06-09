<?php

namespace App\Models;

use App\Enums\AttractionType;
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

    protected $casts = [
        'type' => AttractionType::class,
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
            $query->where('city_id', $filters['city']);
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

