<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Http\Resources\CommentResource;
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
     * @throws DatabaseException
     */
    public function storePost($request): JsonResponse
    {
        try {
           $post =  $this->model->create([
                'blog_id' => $request->blog,
                'name' => $request->name,
                'post' => $request->post
            ]);
            return response()->json([
                'message' => 'Post created successfully',
                'data' => new PostResource($post),
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            throw new DatabaseException("Sorry unable to create post", $e->getMessage());
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
     * @throws DatabaseException
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
                'data' => new PostResource($post),
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            throw new DatabaseException("Sorry unable to update post", $e->getMessage());
        }
    }

    /**
     * @param $slug
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function deletePost($slug): JsonResponse
    {
        $post = $this->model->whereSlug($slug)->firstOrFail();
        try {
            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully',
                'data' => new PostResource($post),
                'success' => true
            ]);
        } catch (\Exception $e) {
            throw new DatabaseException("Sorry unable to delete post", $e->getMessage());
        }
    }

    /**
     * @param  $request
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function commentOnPost($request): JsonResponse
    {
        try {
            $comment = $this->commentModel->create([
                'post_id' => $request->post,
                'comment' => $request->comment,
                'name' => $request->name
            ]);
            return response()->json([
                'message' => 'Comment made successfully',
                'data' => new CommentResource($comment),
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            throw new DatabaseException("Sorry unable to create comment", $e->getMessage());
        }
    }

    /**
     * @param $request
     * @return JsonResponse
     * @throws DatabaseException
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
            throw new DatabaseException("Sorry unable to like post", $e->getMessage());
        }
    }
}
