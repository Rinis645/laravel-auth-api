<?php

namespace App\Policies;

use App\Models\PostStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostStatusPolicy
{
    public function delete(User $user, PostStatus $status): Response
    {
        if ($status->posts()->count() > 0) {
            return Response::deny("Cannot delete status with associated posts");
        }
        return Response::allow();
    }
}
