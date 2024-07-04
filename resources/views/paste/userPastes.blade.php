@extends('layouts.app')

@section('content')
    <h1>{{ $user->name }}'s Profile</h1>
    <p>Email: {{ $user->email }}</p>

    <h2>Recent Pastes</h2>
    <ul>
        @forelse ($userPastes as $paste)
            <li>
                <a href="{{ route('paste.show', $paste->hash) }}">{{ $paste->title }}</a>
            </li>
        @empty
            <li>No pastes found.</li>
        @endforelse
            <div class="pagination">
                {{ $userPastes->links() }}
            </div>
    </ul>
@endsection


