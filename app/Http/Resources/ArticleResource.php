<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'description' => $this->description,
            'descriptionImage' => $this->article_description_image,
            'mainImage' => $this->article_main_image,
            'content' => $this->content,

            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),

            'tags' => $this->whenLoaded('tags', function () {
                return TagResource::collection($this->tags);
            }),

            'comments' => $this->whenLoaded('comments', function () {
                return CommentResource::collection($this->comments);
            }),
        ];
    }
}
