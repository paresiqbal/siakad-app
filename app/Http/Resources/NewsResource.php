<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "image" => $this->image,
            "description" => $this->description,
            "author" => new UserResource($this->whenLoaded('user')), // Load the author's data
            "published_at" => $this->published_at,
        ];
    }
}
