<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentAndLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postsComments = [
            [
                'name' => 'Sarah',
                'comment' => 'Nice  post made Akposieyefa',
            ],
            [
                'name' => 'Maryjane',
                'comment' => 'Nice post made Orutu',
            ],
            [
                'name' => 'Pablo Escobar',
                'comment' => 'Nice elegant post williams',
            ]
        ];
        collect( $postsComments)->each(function($comment) {
            (new \App\Models\Comment)->create([
                'name' => $comment['name'],
                'comment' => $comment['comment'],
                'post_id' =>  (new \App\Models\Post)->inRandomOrder()->first()->id,
            ]);

            //like post
            (new \App\Models\Like)->create([
                'name' => $comment['name'],
                'post_id' =>  (new \App\Models\Post)->inRandomOrder()->first()->id,
            ]);
        });
    }
}
