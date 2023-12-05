@extends('layouts.main')

@section('main')
    <h2>Login</h2>
    <form action="/login" method="post">
        @csrf
        @if (session()->has('success'))
            {{ session('success') }}
        @endif
        <br>

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
        <button type="submit">Login</button>
        <a href="/register">Register</a>
    </form>
@endsection
