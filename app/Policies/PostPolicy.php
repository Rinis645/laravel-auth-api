<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function modify(User $user, Post $post): Response
    {
        return $user->id === $post->user_id ? Response::allow() : Response::deny("Slaying children we are? That boy is our last hope...    ~~Quote by Master Yoda~~");
    }
}
