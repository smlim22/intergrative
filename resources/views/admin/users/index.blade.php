@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container mt-4">
    <h1>User Management</h1>
    
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <form class="d-flex" method="GET" action="">
                <input type="text" class="form-control me-2" name="search" placeholder="Search Users" style="width: 250px;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role_id == 1)
                                Admin
                            @elseif($user->role_id == 2)
                                Student
                            @elseif($user->role_id == 3)
                                Public
                            @else
                                Unknown
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection