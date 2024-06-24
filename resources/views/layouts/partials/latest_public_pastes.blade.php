<hr>
<div>
    <h3>Latest Public Pastes</h3>
    <ul>
        @foreach ($pastes as $paste)
            <li>
                <a href="{{ url('/paste/' . $paste->hash) }}">{{ $paste->title }}</a>
                by {{ $paste->user ? $paste->user->name : 'Anonymous' }}
            </li>
        @endforeach
    </ul>
</div>
