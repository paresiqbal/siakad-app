<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('user')->paginate(10);
        return NewsResource::collection($news);
    }

    public function show($id)
    {
        $news = News::with('user')->findOrFail($id);
        return new NewsResource($news);
    }

    public function store(NewsRequest $request)
    {
        if (!$request->has('user_id')) {
            throw new HttpResponseException(
                response()->json(['error' => 'User ID is required'], 400)
            );
        }

        $news = new News($request->validated());
        $news->user_id = $request->input('user_id');

        $news->save();

        return new NewsResource($news);
    }
}
