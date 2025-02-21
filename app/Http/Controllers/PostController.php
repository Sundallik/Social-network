<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $page = request('page', 1);

        $res = Post::query()->withCount('reposts')->orderByDesc('created_at')->paginate(4, '*', 'page', $page);

        return response()->json([
            'has_next' => (bool)$res->nextPageUrl(),
            'posts' => PostResource::collection($res->items()),
        ]);
    }

    public function followingsPosts()
    {
        $page = request('page', 1);

        $res = Post::whereIn('user_id', auth()->user()->followings->pluck('id'))->paginate(4, '*', 'page', $page);

        return response()->json([
            'has_next' => (bool)$res->nextPageUrl(),
            'posts' => PostResource::collection($res->items()),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $imageId = $data['image_id'] ?? null;
            unset($data['image_id']);

            $post = Post::create($data);

            $this->processImage($post, $imageId);

            DB::commit();
            return PostResource::make($post, $imageId);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => $e->getMessage()], 500);
        }
    }

    private function processImage(Post $post, int|null $imageId) {
        if (isset($imageId)) {
            $image = PostImage::find($imageId);
            $image->update(['post_id' => $post->id]);
        }
        PostImage::clearStorage();
    }

    public function toggleLike(Post $post) {
        $res = $post->users_liked()->toggle(auth()->id());
        $likes_count = $post->users_liked_count;
        return response([
            'is_liked' => count($res['attached']) > 0,
            'likes_count' => count($res['attached']) > 0 ? ++$likes_count  : --$likes_count
        ]);
    }

    public function getComments(Post $post)
    {
        $page = request('page', 1);

        $res = $post->comments()->orderByDesc('created_at')->paginate(3, '*', 'page', $page);

        return response()->json([
            'has_next' => (bool)$res->nextPageUrl(),
            'comments' => CommentResource::collection($res->items())
        ]);
    }
}
