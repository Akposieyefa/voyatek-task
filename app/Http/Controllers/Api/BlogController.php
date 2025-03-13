<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * BlogController
 */
class BlogController extends Controller
{
    /**
     * @param BlogService $service
     */
    public function __construct(private readonly  BlogService $service)
    { }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
       return  $this->service->indexBlog();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'coverImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
           return  $this->service->storeBlog($request);
        }
    }

    /**
     * @param string $slug
     * @return BlogResource
     */
    public function show(string $slug): BlogResource
    {
         return  $this->service->showBlog($slug);
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes',
            'description' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
           return  $this->service->updateBlog($request, $slug);
        }
    }


    /**
     * @param string $slug
     * @return JsonResponse
     * @throws DatabaseException
     */
    public function destroy(string $slug): JsonResponse
    {
        return  $this->service->deleteBlog($slug);
    }

}
