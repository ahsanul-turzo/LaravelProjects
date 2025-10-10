<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tags
        $laravel = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $php = Tag::create(['name' => 'PHP', 'slug' => 'php']);
        $vue = Tag::create(['name' => 'Vue.js', 'slug' => 'vuejs']);
        $tutorial = Tag::create(['name' => 'Tutorial', 'slug' => 'tutorial']);

        // Attach tags to posts
        $post1 = Post::find(1);
        $post1->tags()->attach([$laravel->id, $php->id, $tutorial->id]);

        $post2 = Post::find(2);
        $post2->tags()->attach([$laravel->id, $php->id]);

        $post4 = Post::find(4);
        $post4->tags()->attach([$vue->id, $tutorial->id]);
    }
}
