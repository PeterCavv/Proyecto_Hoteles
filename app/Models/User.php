<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelIdea\Helper\App\Models\_IH_User_C;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, softDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function hotel(): HasOne
    {
        return $this->hasOne(Hotel::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    /**
     * Return the user's friends.
     * @return BelongsToMany
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_1_id', 'user_2_id')
            ->wherePivot('status', 'accepted');
    }

    /**
     * Return the user's friend requests.
     * @return BelongsToMany
     */
    public function pendingFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_1_id', 'user_2_id')
            ->wherePivot('status', 'pending');
    }

    /**
     * Return the user's blocked friends.
     * @return BelongsToMany
     */
    public function blockedFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_1_id', 'user_2_id')
            ->wherePivot('status', 'blocked');
    }

    /**
     * Check if the user is a customer.
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->customer()->exists();
    }

    /**
     * Check if the user is a hotel owner.
     * @return bool
     */
    public function isHotel(): bool
    {
        return $this->hotel()->exists();
    }

    /**
     * Block a user.
     * @param Model|Collection|array|User|_IH_User_C $user2
     * @return bool
     */
    public function block(Model|Collection|array|User|_IH_User_C $user2): void
    {
        if ($user2 instanceof User) {
            $this->blockedFriends()->attach($user2->id, ['status' => 'blocked']);
        } elseif ($user2 instanceof Collection) {
            foreach ($user2 as $user) {
                $this->blockedFriends()->attach($user->id, ['status' => 'blocked']);
            }
        } elseif (is_array($user2)) {
            foreach ($user2 as $user) {
                $this->blockedFriends()->attach($user['id'], ['status' => 'blocked']);
            }
        }
    }

    /**
     * Check if the user is blocked by another user.
     * @param Model|Collection|array|User|_IH_User_C $user2
     * @return bool
     */
    public function isBlockedBy(Model|Collection|array|User|_IH_User_C $user2): bool
    {
        if ($user2 instanceof User) {
            return $this->blockedFriends()->where('user_1_id', $user2->id)->exists();
        } elseif ($user2 instanceof Collection) {
            foreach ($user2 as $user) {
                if ($this->blockedFriends()->where('user_1_id', $user->id)->exists()) {
                    return true;
                }
            }
        } elseif (is_array($user2)) {
            foreach ($user2 as $user) {
                if ($this->blockedFriends()->where('user_1_id', $user['id'])->exists()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function unblock(Model|Collection|array|User|_IH_User_C $user2): void
    {
        if ($user2 instanceof User) {
            $this->blockedFriends()->detach($user2->id);
        } elseif ($user2 instanceof Collection) {
            foreach ($user2 as $user) {
                $this->blockedFriends()->detach($user->id);
            }
        } elseif (is_array($user2)) {
            foreach ($user2 as $user) {
                $this->blockedFriends()->detach($user['id']);
            }
        }
    }
}
