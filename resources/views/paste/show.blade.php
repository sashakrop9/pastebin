<!-- resources/views/show.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>{{ $paste->title }}</h1>
    <p>Posted by {{ $paste->user ? $paste->user->name : 'Anonymous' }} on {{ $paste->created_at->format('M d, Y H:i') }}</p>
    <pre><code class="language-{{ $paste->language }}">{{ $paste->paste_content }}</code></pre>
    @if (Auth::check())
        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf
            <input type="hidden" name="paste_id" value="{{ $paste->id }}">
            <label>
                <textarea name="reason" placeholder="Причина жалобы"></textarea>
            </label>
            <button type="submit">Отправить жалобу</button>
        </form>
    @endif
        @endsection
@section('pastes')
    @include('layouts.partials.latest_public_pastes')
    @include('layouts.partials.latest_user_pastes')
@endsection
