<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Blog::factory()->count(10)->create();
        Post::factory()->count(20)->create();
    }
}
