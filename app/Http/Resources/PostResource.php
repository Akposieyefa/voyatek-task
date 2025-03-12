<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $deleted_at
 * @property mixed $name
 * @property mixed $post
 * @property mixed $blog
 * @property mixed $comments
 * @property mixed $likes
 * @property mixed $slug
 */
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
            'name' => $this->name,
            'post' => $this->post,
            'slug' => $this->slug,
            'relationships' => [
                'blog_post' => new BlogResource($this->blog),
                'comments' => CommentResource::collection($this->comments),
                'like_count' => $this->likes->count()
            ],
            'created_at' => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
