@extends('layouts.app')

@section('content')
    <div class="customProse">
        <h1>Post with Comments</h1>
        <h2>{{$post->title}}</h2>
        <p>By: {{$post->user->name}}</p>
        <h4>{{$post->content}}</h4>

        <h3>Comments ({{$post->comments->count()}}):</h3>
        @foreach($post->comments as $comment)
            <div style='border-left:3px solid #3490dc; padding:10px; margin:10px 0;'>
                <strong>{{$comment->user->name}}:</strong>
                <p>{{$comment->body}}</p>
                <small class="text-gray-400">Posted at: {{$comment->created_at->diffForHumans()}}</small>
            </div>
        @endforeach
    </div>
    <a href="{{ route('test-many-to-many') }}">
        <button class="customButton">Test Many To Many</button>
    </a>
@endsection
