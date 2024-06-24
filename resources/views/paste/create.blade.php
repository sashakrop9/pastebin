<!-- resources/views/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Create a New Paste</h1>
    <form action="{{ url('/paste') }}" method="POST">
        @csrf
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
        </div>
        <div>
            <label for="language">Language:</label>
            <select id="language" name="language" required>
                <option value="plaintext">Plain Text</option>
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <!-- Добавьте другие языки по мере необходимости -->
            </select>
        </div>
        <div>
            <label for="expires_in">Expires in:</label>
            <select id="expires_in" name="expires_in" required>
                <option value="10min">10 minutes</option>
                <option value="1hour">1 hour</option>
                <option value="3hours">3 hours</option>
                <option value="1day">1 day</option>
                <option value="1week">1 week</option>
                <option value="1month">1 month</option>
                <option value="never">Never</option>
            </select>
        </div>
        <div>
            <label for="access">Access:</label>
            <select id="access" name="access" required>
                <option value="public">Public</option>
                <option value="unlisted">Unlisted</option>
                <option value="private">Private</option>
            </select>
        </div>
        <button type="submit">Create</button>
    </form>
@endsection
