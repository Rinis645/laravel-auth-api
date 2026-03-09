<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function modify(User $user, Comment $comment): Response
    {
        return $user->id === $comment->user_id
            ? Response::allow()
            : Response::deny('I am youre father, Luke...');
    }

    /**
     * Only the post owner may flag a comment.
     */
    public function flag(User $user, Comment $comment): Response
    {
        return $user->id === $comment->post->user_id
            ? Response::allow()
            : Response::deny('Only the post author can flag comments');
    }
}