<?php

namespace Database\Seeders;

use App\Models\PostStatus;
use Illuminate\Database\Seeder;

class PostStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'draft', 'description' => 'Post is in draft state, not yet published'],
            ['name' => 'published', 'description' => 'Post has been published and is visible to all'],
            ['name' => 'scheduled', 'description' => 'Post is scheduled for future publication'],
            ['name' => 'review', 'description' => 'Post is pending review before publication'],
            ['name' => 'archived', 'description' => 'Post has been archived'],
            ['name' => 'deleted', 'description' => 'Post has been soft deleted'],
        ];

        foreach ($statuses as $status) {
            PostStatus::create($status);
        }
    }
}
