<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notes', function () {
    // Simulated infinitely nested database structure
    $notes = [
        [
            'id' => 1,
            'title' => 'Project Alpha Planning',
            'content' => 'Main brainstorming session for the new platform launch.',
            'children' => [
                [
                    'id' => 2,
                    'title' => 'Frontend Architecture',
                    'content' => 'Decided to use Laravel, Tailwind, and Livewire.',
                    'children' => [
                        [
                            'id' => 3,
                            'title' => 'Tailwind UI Kit',
                            'content' => 'Need to choose between custom components or TailwindUI templates.',
                            'children' => [] // Can go infinitely deeper
                        ]
                    ]
                ],
                [
                    'id' => 3,
                    'title' => 'Backend API Design',
                    'content' => 'REST endpoints for standard CRUD, WebSockets for notifications.',
                    'children' => []
                ],
                [
                    'id' => 4,
                    'title' => 'Frontend API Implementation',
                    'content' => 'REST endpoints for standard CRUD, WebSockets for notifications.',
                    'children' => []
                ]
            ]
        ],
        [
            'id' => 5,
            'title' => 'Personal Reminders',
            'content' => 'Don\'t forget to buy milk and walk the dog.',
            'children' => []
        ]
    ];
    return view('notes.index', compact('notes'));
})->name('notes');
