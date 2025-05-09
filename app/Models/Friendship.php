<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => FriendshipStatus::class,
    ];

    protected $fillable = [
        'user_1_id',
        'user_2_id',
        'status'
    ];

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_1_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_2_id');
    }

    // Friendship management

    /**
     * Accept the friendship request and save the status.
     * @return void
     */
    public function accept(): void
    {
        $this->status = FriendshipStatus::ACCEPTED;
        $this->save();
    }

    /**
     * Block the relationship between users and save the status.
     * @return void
     */
    public function blocked(): void
    {
        $this->status = FriendshipStatus::BLOCKED;
        $this->save();
    }

    /**
     * Reject the friendship request and delete the relationship.
     * @return void
     */
    public function rejected(): void
    {
        $this->delete();
    }

    // End Friendship management
}
