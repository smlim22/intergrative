@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <h1>Welcome, {{ Auth::user()->name }}</h1>
    <p>Your role ID: {{ Auth::user()->role_id }}</p>
    @if(Auth::user()->role_id == 1)
        <p>Your role: Admin</p>
    @elseif(Auth::user()->role_id == 2)
        <p>Your role: Student</p>
    @elseif(Auth::user()->role_id == 3)
        <p>Your role: Public</p>
    @else
        <p>Your role: Unknown</p>
    @endif
    {{-- If you have a role relationship --}}
    {{-- <p>Your role: {{ Auth::user()->role->name }}</p> --}}
</div>
@endsection