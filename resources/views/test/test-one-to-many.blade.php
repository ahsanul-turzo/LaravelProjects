@php use App\Models\Post; @endphp
@extends('layouts.app')

@section('content')
    <article class="customProse">

        <h1>One-to-Many Relationship Test</h1>
        <h2>Author: {{$user->name}}</h2>
        <p>Total Posts: {{$user->posts()->count()}}</p>
        <h3>All Posts by {{$user->name}}:</h3>

        @foreach($user->posts as $post)
            <div style='border:1px solid #ccc; padding:10px; margin:10px 0;'>
                <h4>{{$post->title}} {{$status}}</h4>
                <p>{{$post->content}}</p>
            </div>
        @endforeach

        <hr>

        <h3>Post: {{$post->title}}</h3>
        <p>Written by: {{$post->user->name}}</p>
        <p>Author's Email: {{$post->user->email}}</p>
    </article>
    <a href="{{ route('test-comments') }}">
        <button class="customButton">Go To Comments</button>
    </a>
@endsection
