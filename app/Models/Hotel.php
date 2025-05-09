<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'location',
        'city',
        'postal_code',
        'rating'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'hotel_features');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(
            Customer::class,
            'follows',
            'hotel_id',
            'customer_id'
        )->withPivot('followed_at')->withTimestamps();
    }
}
