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

    public function index(Request $request, Post $post)
    {
        $query = $post->comments();

        // determine user if a bearer token was provided (index/show bypass auth middleware)
        $user = $request->user();
        if (!$user && $request->bearerToken()) {
            $user = \Illuminate\Support\Facades\Auth::guard('sanctum')->user();
        }

        // if the requesting user isn't the owner, hide flagged comments
        if (!$user || $user->id !== $post->user_id) {
            $query->whereNull('flagged_at');
        }

        $comments = $query->get();
        return response()->json($comments, 200);
    }

    public function show(Request $request, Post $post, Comment $comment)
    {
        // hide flagged comment from non-post owners
        // note: $post is provided by the nested route but not used directly here
        // resolve optional bearer token (show also bypasses auth middleware)
        $user = $request->user();
        if (!$user && $request->bearerToken()) {
            $user = \Illuminate\Support\Facades\Auth::guard('sanctum')->user();
        }

        if ($comment->flagged_at && (!$user || $user->id !== $comment->post->user_id)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

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

    /**
     * Flag a comment; only the post author can do this.
     */
    public function flag(Request $request, Post $post, Comment $comment)
    {
        Gate::authorize('flag', $comment);

        $comment->update(['flagged_at' => now()]);

        return response()->json($comment, 200);
    }
}