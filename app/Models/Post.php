<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comments;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'post_status_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(PostStatus::class, 'post_status_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Global scope: only return published posts by default.
     *
     * In this app "published" means post_status_id === 2 but you can adapt
     * the predicate to whatever your status table uses.
     */
    protected static function booted()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('post_status_id', 2);
        });
    }

    /**
     * Local scope for eager‑loading comments when querying posts.
     * Usage: Post::withComments()->get();
     */
    public function scopeWithComments(Builder $query)
    {
        return $query->with('comments');
    }
}