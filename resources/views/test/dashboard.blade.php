@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="customProse">
        <h1>🎯 Laravel Relationships Dashboard</h1>

        @foreach($users as $user)
            <div class='user-card'>
                <h2>👤 {{$user->name}}</h2>
                <p>📧 Email: {{$user->email}}</p>

                <code>// One-to-One: Profile</code>
                @if($user->profile)
                    <div style='background:#e3fcef; padding:10px; border-radius:5px;'>
                        <strong>Profile:</strong>
                        <p>Bio: {{$user->profile->bio}}</p>
                        <p>Website: {{$user->profile->website}}</p>
                    </div>
                @endif

                <code>// One-to-Many: Posts</code>
                <h3>📝 Posts ({{$user->posts->count()}})</h3>
                @foreach($user->posts as $post)
                    <div class='post'>
                        <h4>{{$post->title}}</h4>

                        <code>// Many-to-Many: Tags</code>
                        <p>Tags:
                            @foreach($post->tags as $tag)
                                <span class='tag'>{{$tag->name}}</span>
                            @endforeach
                        </p>

                        <code>// One-to-Many: Comments</code>
                        <strong>💬 Comments ({{$post->comments->count()}}):</strong>
                        @foreach($post->comments as $comment)
                            <div class='comment'>
                                <strong>{{$comment->user->name}}:</strong>
                                <p>{{$comment->body}}</p>
                            </div>
                        @endforeach

                    </div>
                @endforeach

            </div>
        @endforeach
    </div>
@endsection
