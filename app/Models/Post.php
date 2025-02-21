<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $with = ['user', 'image', 'users_liked', 'parent'];
    protected $withCount = ['users_liked', 'comments'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function image() {
        return $this->hasOne(PostImage::class);
    }

    public function parent() {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function users_liked() {
        return $this->belongsToMany(User::class, 'post_user_likes', 'post_id', 'user_id');
    }

    public function reposts() {
        return $this->hasMany(Post::class, 'parent_id', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
