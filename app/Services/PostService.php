<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

readonly class PostService
{
    /**
     * @param Post $model
     * @param Like $likeModel
     * @param Comment $commentModel
     */
    public function __construct(private Post $model, private Like $likeModel, private Comment $commentModel)
    {}

    /**
     * @return AnonymousResourceCollection
     */
    public function indexPosts(): AnonymousResourceCollection
    {
        $blogs = $this->model->with(['likes', 'comments'])->latest()->paginate(10);
        return PostResource::collection($blogs)->additional([
            'message' => "All post fetched successfully",
            'success' => true
        ], Response::HTTP_OK);
    }

    /**
     * @param  $request
     * @return JsonResponse
     */
    public function storePost($request): JsonResponse
    {
        try {
            $this->model->create([
                'blog_id' => $request->blog,
                'name' => $request->name,
                'post' => $request->post
            ]);
            return response()->json([
                'message' => 'Post created successfully',
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param  $slug
     * @return PostResource
     */
    public function showPost($slug): PostResource
    {
        $post = $this->model->whereSlug($slug)->firstOrFail();
        return (new PostResource($post))->additional( [
            'message' => "Post details fetched successfully",
            'success' => true
        ], Response::HTTP_OK);
    }

    /**
     * @param  $request
     * @param  $slug
     * @return JsonResponse
     */
    public function updatePost($request,$slug): JsonResponse
    {
        $post = $this->model->whereSlug($slug)->firstOrFail();
        $post->slug = null;
        try {
            $post->update([
                'name' => empty($request->name) ? $post->name : $request->name,
                'post' => empty($request->post) ? $post->post : $request->post
            ]);
            return response()->json([
                'message' => 'Post updated successfully',
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $slug
     * @return JsonResponse
     */
    public function deletePost($slug): JsonResponse
    {
        $post = $this->model->whereSlug($slug)->firstOrFail();
        try {
            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param  $request
     * @return JsonResponse
     */
    public function commentOnPost($request): JsonResponse
    {
        try {
            $this->commentModel->create([
                'post_id' => $request->post,
                'comment' => $request->comment,
                'name' => $request->name
            ]);
            return response()->json([
                'message' => 'Comment made successfully',
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function likePost($request): JsonResponse
    {
        try {
            $this->likeModel->create([
                'post_id' => $request->post,
                'name' => $request->name
            ]);
            return response()->json([
                'message' => 'Post liked successfully',
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
