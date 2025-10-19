<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1= User::find(1);
        $user2= User::find(2);

        $user1->posts()->create([
            'title' => 'Laravel Relationships Guide',
            'content' => 'This is my first post about Laravel relationships...',
            'published' => true,
        ]);

        $user1->posts()->create([
            'title' => 'Building APIs with Laravel',
            'content' => 'Today I learned how to build RESTful APIs...',
            'published' => true,
        ]);

        $user1->posts()->create([
            'title' => 'Laravel Tips and Tricks',
            'content' => 'Here are some useful Laravel tips...',
            'published' => false,
        ]);

        // User 2 creates 2 posts
        $user2->posts()->create([
            'title' => 'Getting Started with Vue.js',
            'content' => 'Vue.js is an amazing framework...',
            'published' => true,
        ]);

        $user2->posts()->create([
            'title' => 'Frontend Best Practices',
            'content' => 'Here are some frontend best practices...',
            'published' => true,
        ]);
    }
}
