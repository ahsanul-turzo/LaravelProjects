@extends('layouts.app')

@section('content')
    <article class="customProse">
        <h1>One-to-One Relationship Test</h1>
        <h2>User: {$user->name}</h2>
        <p>Email: {$user->email}</p>
        <h3>Profile:</h3>
        <p>Bio: {$user->profile->bio}</p>
        <p>Website: {$user->profile->website}</p>

        <div class="my-2">
            <hr>
        </div>

        <p>// Get profile with user (reverse)</p>
        <p>$profile = Profile::with('user')->find(1)</p>
        <h3>Profile belongs to:</h3>
        <p>User: {$profile->user->name}</p>
    </article>
    <div class="h-5"></div>
    <a href="{{ route('test-one-to-many') }}">
        <button class="customButton">Go To Next</button>
    </a>
@endsection


