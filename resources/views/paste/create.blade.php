<!-- resources/views/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Create New Paste</h1>
    <form action="{{ route('paste.store') }}" method="POST">
        @csrf
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="paste_content">Content:</label>
            <textarea id="paste_content" name="paste_content" required></textarea>
        </div>
        <div>
            <label for="language">Language:</label>
            <select id="language" name="language" required>
                <option value="plaintext">Plain Text</option>
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>

            </select>
        </div>
        <div>
            <label for="expires_at">Expires in:</label>
            <select id="expires_at" name="expires_at" required>
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

    <hr>

    <h1>тут должны быть пасты</h1>
@endsection
