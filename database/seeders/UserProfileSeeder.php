<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);
        // Create profile for user
        Profile::create([
            'user_id' => $user->id,
            'bio' => 'Laravel developer and blogger',
            'website' => 'https://johndoe.com',
            'avatar' => 'john.jpg',
        ]);
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);
        // Create profile using relationship
        $user2->profile()->create([
            'bio' => 'Tech enthusiast and writer',
            'website' => 'https://janesmith.com',
            'avatar' => 'jane.jpg',
        ]);
    }
}
