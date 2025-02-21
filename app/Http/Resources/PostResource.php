<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
/**
 * Transform the resource into an array.
 *
 * @return array<string, mixed>
 */
public function toArray(Request $request): array
{
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->diffForHumans(),
            'image' => PostImageResource::make($this->image),
            'user' => UserResource::make($this->user),
            'parent' => PostResource::make($this->parent),
            'likes_count' => $this->users_liked_count,
            'reposts_count' => $this->reposts_count,
            'comments_count' => $this->comments_count,
            'authorized_is_liked' => $this->users_liked->pluck('id')->contains(auth()->id()),
        ];
    }
}
