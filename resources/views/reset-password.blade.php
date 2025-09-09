
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container mt-4 d-flex align-items-center justify-content-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            <div class="card shadow-lg p-4 rounded-3">
                <h2 class="text-center mb-4">Reset Password</h2>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input class="form-control" type="password" name="password" placeholder="New Password" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
                <hr>
                <div class="text-center mb-3">
                    <a href="{{ route('login') }}">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection