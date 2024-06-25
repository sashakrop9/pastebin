<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('content')

    <h1><a href="{{ route('paste.create') }}">Create new paste</a></h1>

@endsection

@section('pastes')

    @include('layouts.partials.latest_public_pastes')
    @include('layouts.partials.latest_user_pastes')
@endsection
