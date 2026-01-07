<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    public function post()
    {
        return $this->hasMany(post::class);
    }
    // 1. Who am I following? (The Stars)
    public function following() {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'following_id');
    }

    // 2. Who follows me? (The Fans)
    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'user_id');
    }

    // Helper: Check if I already follow someone
    public function isFollowing(User $user) {
        return $this->following()->where('following_id', $user->id)->exists();
    }
}
