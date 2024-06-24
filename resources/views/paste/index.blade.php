<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Recent Public Pastes</h1>
    <ul>
        @foreach ($pastes as $paste)
            <li>
                <a href="{{ url('/paste/' . $paste->hash) }}">{{ $paste->title }}</a>
                by {{ $paste->user ? $paste->user->name : 'Anonymous' }}
            </li>
        @endforeach
    </ul>

    @if (Auth::check())
        <h2>My Recent Pastes</h2>
        <ul>
            @foreach ($userPastes as $paste)
                <li>
                    <a href="{{ url('/paste/' . $paste->hash) }}">{{ $paste->title }}</a>
                </li>
            @endforeach
            @else
                <p>You need to <a href="{{ url('/login') }}">login</a> to see your pastes.</p>
    @endif
@endsection
