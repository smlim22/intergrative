<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showRegister()
    {
        return view('/register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, //Default value for public user
            'status' => $request->status ?? 'Active',
            'student_id' => null,
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function showLogin()
    {
        return view('/login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user status is Active
            if (Auth::user()->status !== 'Active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact the administrator.',
                ]);
            }

            $role = Auth::user()->role->name;

            return match ($role) {
                'admin' => redirect('/admin'),
                'student' => redirect('/student'),
                'public' => redirect('/'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return back()->with('success', 'User activated successfully.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->save();

        return back()->with('success', 'User deactivated successfully.');
    }
}
