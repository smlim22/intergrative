@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container mt-4">
    <h1>User Management</h1>
    
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <form class="d-flex" method="GET" action="">
                <input type="text" class="form-control me-2" name="search" placeholder="Search Users" style="width: 250px;">
                <select name="role" class="form-select me-2" style="width: 150px;">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
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
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @if($user->role_id != 1)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role_id == 2)
                                    Student
                                @elseif($user->role_id == 3)
                                    Public
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($user->status === 'Active')
                                        <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.activate', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Activate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection