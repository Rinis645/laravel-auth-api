<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index(Post $post)
    {
        $comments = $post->comments()->get();
        return response()->json($comments, 200);
    }

    public function show(Comment $comment)
    {
        return response()->json($comment, 200);
    }

    public function store(Request $request, Post $post)
    {
        $user = $request->user();

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => $user->id,
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, Post $post, Comment $comment)
{
    Gate::authorize('modify', $comment);

    $validated = $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $comment->update($validated);

    return response()->json($comment, 200);
}

public function destroy(Post $post, Comment $comment)
{
    Gate::authorize('modify', $comment);

    $comment->delete();

    return response()->json([
        'message' => 'Comment deleted'
    ], 200);
}
}