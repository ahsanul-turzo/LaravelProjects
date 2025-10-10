@extends('layouts.app')

@section('title', 'Many To Many')

@section('content')

    <article class="customProse">
        <h1>Many-to-Many Relationship Test</h1>
        <h2>{{$post->title}}</h2>
        <div class="h-4"></div>
        <p>
            Tags:
            @foreach($post->tags as $tag)
                <span class="bg-blue-500 rounded-sm px-4 py-1">{{$tag->name}}</span>
            @endforeach
        </p>

        <div class="my-5">
            <hr>
        </div>

        <h2>All posts tagged with '{{$postTag->name}}':</h2>
        <div class="h-2"></div>
        @foreach($postTag->posts as $post)
            <div class="border px-5 py-2 rounded-xl my-3">
                <h3>{{$post->title}}</h3>
                <p>By: {{$post->user->name}}</p>
            </div>
        @endforeach
        
        <a href="{{ route('dashboard') }}">
            <button class="customButton">Go To Dashboard</button>
        </a>
    </article>
@endsection
