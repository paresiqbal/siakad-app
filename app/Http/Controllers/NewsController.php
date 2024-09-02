<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Request;

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

        // Check if an image was uploaded
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news_images', 'public');
            $data['image'] = $path;
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
    public function index(Request $request)
    {
        $query = News::query();

        // Sorting
        if ($request->has('sort_by')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order', 'asc'));
        }

        return NewsResource::collection($query->paginate(10));
    }

    // delete news
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json(['message' => 'News article deleted successfully.'], 200);
    }
}
