<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewsController extends Controller
{
    // create news
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

        $news = new News($data);
        $news->save();

        return new NewsResource($news);
    }

    // update news
    public function update(NewsRequest $request, $id): NewsResource
    {
        $data = $request->validated();

        $news = News::findOrFail($id);

        // Check if a different news article with the same title already exists
        if (News::where("title", $data["title"])
            ->where("id", "<>", $id)
            ->exists()
        ) {
            throw new HttpResponseException(response([
                "errors" => [
                    "title" => ["Another news article with this title already exists"],
                ],
            ], 400));
        }

        $news->update($data);

        return new NewsResource($news);
    }

    // show specific news
    public function show($id): NewsResource
    {
        $news = News::findOrFail($id);

        return new NewsResource($news);
    }

    // show all news
    public function index()
    {
        $news = News::paginate(10);

        return NewsResource::collection($news);
    }
}
