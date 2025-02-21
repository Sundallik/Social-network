<?php

namespace App\Http\Resources\User;

use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPersonalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        $posts = $this->posts()->withCount('reposts')->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->year,
            'authorized_is_follower' => $this->followers->contains(auth()->id()),
            'followers_count' => $this->followers->count(),
            'subscribes_count' => $this->followings->count(),
            'posts_count' => $this->posts->count(),
            'likes_count' => $this->posts->pluck('users_liked_count')->sum(),
        ];
    }
}
