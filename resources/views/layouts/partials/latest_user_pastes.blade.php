<hr>
@if (Auth::check())
    <h2>My Recent Pastes</h2>
    <ul>
        @foreach ($userPastes as $paste)
            <li>
                <a href="{{ route('paste.show',[$paste->hash]) }}">{{ $paste->title }}</a>
            </li>
        @endforeach
        @else
            <p>You need to <a href="{{ route('login') }}">login</a> to see your pastes.</p>
@endif
