<!-- resources/views/show.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>{{ $paste->title }}</h1>
    <p>Posted by {{ $paste->user ? $paste->user->name : 'Anonymous' }} on {{ $paste->created_at->format('M d, Y H:i') }}</p>
    <pre><code class="language-{{ $paste->language }}">{{ $paste->paste_content }}</code></pre>
@endsection
