<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'name' => 'Orutu Akposieyefa W',
                'post' => 'Nice blog post made',
            ],
            [
                'name' => 'Orutu Akposieyefa',
                'post' => 'Nice blog post made by voyatek',
            ],
            [
                'name' => 'Orutu Akposieyefa Williams',
                'post' => 'Nice blog post made by voyatek',
            ]
        ];

        collect( $posts)->each(function($post) {
            (new \App\Models\Post)->create([
                'name' => $post['name'],
                'post' => $post['post'],
                'blog_id' =>  (new \App\Models\Blog)->inRandomOrder()->first()->id,
            ]);
        });
    }
}
