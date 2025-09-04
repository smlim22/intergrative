<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return view('/admin');
    }

    public function users(Request $request) {
        $query = User::query();
        $users = $query->get();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            $users = $query->get();
        }

        return view('admin.users.index', compact('users'));
    }
}
