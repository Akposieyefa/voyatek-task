<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

readonly class BlogService
{
    /**
     * @param Blog $model
     */
    public function __construct(private Blog $model)
    { }

    /**
     * @return AnonymousResourceCollection
     */
    public function indexBlog(): AnonymousResourceCollection
    {
        $blogs = $this->model->with(['posts'])->latest()->paginate(10);
        return BlogResource::collection($blogs)->additional([
            'message' => "All blogs fetched successfully",
            'success' => true
        ], Response::HTTP_OK);
    }

    /**
     * @param $request
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function storeBlog($request): JsonResponse
    {
        try {
            $blog = $this->model->create([
                'title' => $request->title,
                'description' => $request->description
            ]);
            return response()->json([
                'message' => 'Blog created successfully',
                'data' => new BlogResource($blog),
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            throw new DatabaseException("Sorry unable to create blog", $e->getMessage());
        }
    }

    /**
     * @param $slug
     * @return BlogResource
     */
    public function showBlog($slug): BlogResource
    {
        $blog = $this->model->whereSlug($slug)->firstOrFail();
        return (new BlogResource($blog))->additional( [
            'message' => "Blog details fetched successfully",
            'success' => true
        ], Response::HTTP_OK);
    }

    /**
     * @param $request
     * @param $slug
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function updateBlog($request,$slug): JsonResponse
    {
        $blog = $this->model->whereSlug($slug)->firstOrFail();
        $blog->slug = null;
        try {
            $blog->update([
                'title' => empty($request->title) ? $blog->title : $request->title,
                'description' => empty($request->description) ? $blog->description : $request->description
            ]);
            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => new BlogResource($blog),
                'success' => true
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            throw new DatabaseException("Something unable to update blog", $e->getMessage());
        }
    }


    /**
     * @param string $slug
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function deleteBlog(string $slug): JsonResponse
    {
        $blog = $this->model->whereSlug($slug)->firstOrFail();
        try {
            $blog->delete();
            return response()->json([
                'message' => 'Blog deleted successfully',
                'data' => new BlogResource($blog),
                'success' => true
            ]);
        } catch (\Exception $e) {
            throw new DatabaseException("Sorry unable to delete blog", $e->getMessage());
        }
    }

}
