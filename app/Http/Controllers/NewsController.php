<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        // Ensure that the authenticated user is retrieved
        $user = $request->user();

        // Add the 'user_id' field to the fields array
        $fields['user_id'] = $user->id;

        // Create the news associated with the user
        $news = $user->news()->create($fields);

        return new NewsResource($news);
    }


    public function update(NewsRequest $request, $id)
    {
        Gate::authorize('modify', News::findOrFail($id));

        $news = News::findOrFail($id);

        if ($request->user()->id !== $news->user_id) {
            return response()->json(['error' => 'You are not authorized to update this news post'], 403);
        }

        $fields = $request->validated();
        $news->update($fields);

        return new NewsResource($news);
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('modify', News::findOrFail($id));

        $news = News::findOrFail($id);

        if ($request->user()->id !== $news->user_id) {
            return response()->json(['error' => 'You are not authorized to delete this news post'], 403);
        }

        $news->delete();

        return response()->json(['message' => 'News post deleted successfully'], 200);
    }
}
