<hr>
@if (Auth::check())
    <h2>My Recent Pastes</h2>
    <ul>
        @foreach ($userPastes as $paste)
            <li>
                <a href="{{ url('/paste/' . $paste->hash) }}">{{ $paste->title }}</a>
                {{$paste->user_id}}
            </li>
        @endforeach
        @else
            <p>You need to <a href="{{ url('/login') }}">login</a> to see your pastes.</p>
@endif
