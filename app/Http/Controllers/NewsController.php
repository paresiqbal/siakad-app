<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NewsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

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
        $fields = $request->validated();
        $user = $request->user();
        $news = $user->newses()->create($fields);

        return new NewsResource($news);
    }

    public function update(NewsRequest $request, $id)
    {
        $news = News::findOrFail($id);

        if ($request->user()->id !== $news->user_id) {
            return response()->json(['error' => 'You are not authorized to update this news post'], 403);
        }

        $fields = $request->validated();
        $news->update($fields);

        return new NewsResource($news);
    }
}
