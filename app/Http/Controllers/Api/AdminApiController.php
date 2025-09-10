<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminApiController extends Controller
{
    /**
     * List users (excluding admins)
     */
    public function index(Request $request)
    {
        $query = User::with('role')->where('role_id', '!=', 1);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->paginate(10);

        return response()->json($users);
    }

    /**
     * View a single user
     */
    public function show(User $user)
    {
        return response()->json($user->load('role'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => 'sometimes|string|max:255',
            'email'        => 'sometimes|email|unique:users,email,' . $user->id,
            'phone_number' => 'sometimes|string|min:10|max:11',
            'status'       => 'sometimes|in:Active,Inactive',
            'role_id'      => 'sometimes|exists:roles,id'
        ]);

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated successfully',
            'data'    => $user
        ]);
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Activate user
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'Active']);

        return response()->json(['message' => 'User activated successfully']);
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        $user->update(['status' => 'Inactive']);

        return response()->json(['message' => 'User deactivated successfully']);
    }
}
