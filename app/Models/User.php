<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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

    public function posts() {
        return $this->hasMany(Post::class)->latest();
    }

    public function followings() {
        return $this->belongsToMany(User::class, 'subscriber_followings', 'subscriber_id', 'following_id');
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'subscriber_followings', 'following_id', 'subscriber_id');
    }

    public function postsLiked() {
        return $this->belongsToMany(User::class, 'post_user_likes', 'user_id', 'post_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
