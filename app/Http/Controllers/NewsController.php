<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewsController extends Controller
{
    public function create(NewsRequest $request): NewsResource
    {
        $data = $request->validated();

        // Check if a news article with the same title already exists
        if (News::where("title", $data["title"])->exists()) {
            throw new HttpResponseException(response([
                "errors" => [
                    "title" => ["News with this title already exists"],
                ],
            ], 400));
        }

        // Create a new news article
        $news = new News($data);
        $news->save();

        // Return the newly created news item as a resource
        return new NewsResource($news);
    }
}
