<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'customer_id',
        'check_in',
        'check_out',
        'adults',
        'children',
        'room_type_id',
        'price'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    /**
     * Get the customer associated with the model.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the room type associated with the model.
     *
     * @return BelongsTo
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Define the relationship between the current model and the Hotel model.
     *
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope a query to filter reservations based on the provided filters.
     *
     * Available filters:
     * - customer_name: Filter by customer name (partial match).
     * - customer_email: Filter by customer email (exact match).
     * - hotel_city: Filter by hotel city (exact match).
     * - min_price: Filter reservations with a price greater than or equal to the given value.
     * - max_price: Filter reservations with a price less than or equal to the given value.
     *
     * @param Builder $query
     * @param array $filters The array of filters to apply.
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->select('reservations.*')
            ->join('customers', 'reservations.customer_id', '=', 'customers.id')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->join('hotels', 'reservations.hotel_id', '=', 'hotels.id')
            ->when($filters['customer_name'] ?? false, function ($q, $name) {
                $q->where('users.name', 'like', "%{$name}%");
            })
            ->when($filters['customer_email'] ?? false, function ($q, $email) {
                $q->where('users.email', $email);
            })
            ->when($filters['hotel_city'] ?? false, function ($q, $city) {
                $q->where('hotels.city', $city);
            })
            ->when($filters['min_price'] ?? false, function ($q, $min) {
                $q->where('reservations.price', '>=', $min);
            })
            ->when($filters['max_price'] ?? false, function ($q, $max) {
                $q->where('reservations.price', '<=', $max);
            })
            ->with(['customer.user', 'hotel']);
    }
}
