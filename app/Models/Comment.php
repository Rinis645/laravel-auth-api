<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id','user_id','content', 'flagged_at'];

    protected $casts = [
        'flagged_at' => 'datetime',
    ];

    public function user() {
       return $this->belongsTo(User::class);
    }

    public function post() {
       return $this->belongsTo(Post::class);
    }
}
