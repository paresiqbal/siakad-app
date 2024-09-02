<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // Create news
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

    // Update news
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

        // Check if a new image was uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $path = $request->file('image')->store('news_images', 'public');
            $data['image'] = $path;
        }

        $news->update($data);

        return new NewsResource($news);
    }

    // Show specific news
    public function show($id): NewsResource
    {
        $news = News::findOrFail($id);

        return new NewsResource($news);
    }

    // Show all news
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('author')) {
            $query->where('author', $request->input('author'));
        }

        return NewsResource::collection($query->paginate(10));
    }

    // Delete news
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json(['message' => 'News article deleted successfully.'], 200);
    }
}
