<?php

namespace Database\Seeders;

use App\Models\NoteV2;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NoteV2::factory()
            ->count(5)
            ->create();
    }
}
