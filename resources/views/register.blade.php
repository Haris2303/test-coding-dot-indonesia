@extends('layouts.main')

@section('main')
    <h2>Register</h2>
    <form action="/register" method="post">
        @csrf

        @error('errors')
            {{ $message }}
        @enderror

        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}">
        @error('name')
            {{ $message }}
        @enderror

        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}">
        @error('username')
            {{ $message }}
        @enderror

        <br>
        <input type="password" name="password" placeholder="Password">
        @error('password')
            {{ $message }}
        @enderror

        <br>
        <button type="submit">Register</button>
        <a href="/login">Login</a>
    </form>
@endsection
