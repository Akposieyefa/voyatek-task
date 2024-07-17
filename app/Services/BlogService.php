<?php

namespace App\Services;

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
     * @param string $slug
     * @return JsonResponse
     */
    public function deleteBlog($slug): JsonResponse
    {
        $blog = $this->model->whereSlug($slug)->firstOrFail();
        try {
            $blog->delete();
            return response()->json([
                'message' => 'Blog deleted successfully',
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
