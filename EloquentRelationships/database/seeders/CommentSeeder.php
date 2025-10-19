<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $post1 = Post::find(1);

        // User 2 comments on User 1's post
        $post1->comments()->create([
            'user_id' => $user2->id,
            'body' => 'Great post! Very informative.' . 'Best regards: ' . $user1->name . ' ' . $user2->name . '!',
        ]);

        $post1->comments()->create([
            'user_id' => $user2->id,
            'body' => 'Thanks for sharing this!',
        ]);

        // User 1 replies
        $post1->comments()->create([
            'user_id' => $user1->id,
            'body' => 'Glad you found it helpful!',
        ]);
    }
}
