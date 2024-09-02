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

    public function update(NewsRequest $request, $id): NewsResource
    {
        $data = $request->validated();

        // Find the news article by ID
        $news = News::findOrFail($id);

        // Check if a different news article with the same title already exists
        if (News::where("title", $data["title"])
            ->where("id", "<>", $id) // Exclude the current article
            ->exists()
        ) {
            throw new HttpResponseException(response([
                "errors" => [
                    "title" => ["Another news article with this title already exists"],
                ],
            ], 400));
        }

        // Update the news article with the new data
        $news->update($data);

        // Return the updated news item as a resource
        return new NewsResource($news);
    }

    public function show($id): NewsResource
    {
        // Find the news article by ID
        $news = News::findOrFail($id);

        // Return the news item as a resource
        return new NewsResource($news);
    }

    public function index()
    {
        // Retrieve all news articles with pagination
        $news = News::paginate(10); // Adjust the number for the number of items per page

        // Return the collection of news articles as a resource
        return NewsResource::collection($news);
    }
}
