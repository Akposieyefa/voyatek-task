<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * PostController
 */
class PostController extends Controller
{
    /**
     * @param PostService $service
     */
    public function __construct(private readonly PostService $service)
    {}

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return  $this->service->indexPosts();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
           return  $this->service->storePost($request);
        }
    }

    /**
     * @param string $slug
     * @return PostResource
     */
    public function show(string $slug): PostResource
    {
       return  $this->service->showPost($slug);
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function update(Request $request, string $slug): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes',
            'post' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
          return  $this->service->updatePost($request, $slug);
        }
    }

    /**
     * @param string $slug
     * @return JsonResponse
     */
    public function destroy(string $slug): JsonResponse
    {
       return  $this->service->deletePost($slug);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function comments(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'post' =>'required',
            'name' =>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
           return  $this->service->commentOnPost($request);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function like(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'post' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else {
           return  $this->service->likePost($request);
        }
    }

}
