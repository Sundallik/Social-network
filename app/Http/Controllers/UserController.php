<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\FollowRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserPersonalResource;
use App\Http\Resources\User\UserResource;
use App\Models\Post;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $page = request('page', 1);

        $res = User::whereNot('id', auth()->id())->with('followers')->paginate(10, '*', 'page', $page);

        return response()->json([
            'has_next' => (bool)$res->nextPageUrl(),
            'users' => UserListResource::collection($res->items()),
        ]);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->with('posts', 'followers')->first();
        return UserPersonalResource::make($user);
    }

    public function getPosts($id)
    {
        $page = request('page', 1);

        $res = Post::where('user_id', $id)->withCount('reposts')->orderByDesc('created_at')->paginate(4, '*', 'page', $page);

        return response()->json([
            'has_next' => (bool)$res->nextPageUrl(),
            'posts' => PostResource::collection($res->items()),
        ]);
    }

    public function getAuthorizedUser() {
        $user = User::find(auth()->id());
        return UserResource::make($user);
    }

    public function toggleFollowing(FollowRequest $request) {
        $data = $request->validated();
        $res = auth()->user()->followings()->toggle($data['following_id']);
        return response(['is_following' => count($res['attached']) > 0]);
    }
}
