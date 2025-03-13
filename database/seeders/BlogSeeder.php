<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'General Blog for Voyatek Group One',
                'description' => 'General Blog for Voyatek Group One',
                'cover_image' =>'dog-training-2.jpg'
            ],
            [
                'title' => 'General Blog for Voyatek Group Two',
                'description' => 'General Blog for Voyatek Group Two',
                'cover_image' =>  'dog.jpg'
            ],
            [
                'title' => 'General Blog for Voyatek Group Three',
                'description' => 'General Blog for Voyatek Group Three',
                'cover_image' => 'dog-training-2.jpg'
            ]
        ];

        collect( $blogs)->each(function($blog) {
            (new \App\Models\Blog)->create([
                'title' => $blog['title'],
                'description' => $blog['description'],
                'cover_image' => $blog['cover_image']
            ]);
        });
    }
}
