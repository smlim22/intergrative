<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return view('/admin');
    }

    public function users(Request $request) {
        $query = User::with('role')->where('role_id', '!=', 1); // Exclude admin users

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->get();
        $roles = Role::where('id', '!=', 1)->get(); // Exclude admin role from dropdown

        return view('admin.users.index', compact('users', 'roles'));
    }

}
