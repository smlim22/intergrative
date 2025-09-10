@extends('layouts.app')

@section('title', 'User Details - '. $user->name)

@section('content')

<div class="container mt-4">
    <h1>User Details</h1>
    
    <div class="card mt-3">
        <div class="card-body">
            <h3 class="card-title mb-3">{{ $user->name }}</h3>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Role:</strong> 
                @if($user->role_id == 2)
                    Student
                @elseif($user->role_id == 3)
                    Public
                @else
                    Unknown
                @endif
            </p>
            @if ($user->role_id == 2)
                <p class="card-text"><strong>Student ID:</strong> {{ $user->student_id }}</p>
            @endif
            <p class="card-text"><strong>Phone Number:</strong> {{ $user->phone_number }}</p>
            <p class="card-text"><strong>Joined On:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $user->status }}</p>
            <a href="{{ route('users.index') }}" class="btn btn-primary">Back to User List</a>
        </div>
    </div>
</div>
@endsection