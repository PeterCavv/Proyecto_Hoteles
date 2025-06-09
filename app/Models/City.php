<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'description',
    ];

    public function attractions(): HasMany|City
    {
        return $this->hasMany(Attraction::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
}
