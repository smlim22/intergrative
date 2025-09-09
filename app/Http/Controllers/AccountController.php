<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
            'phone_number' => 'required|string|min:10|max:11',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, //Default value for public user
            'status' => $request->status ?? 'Active',
            'student_id' => null,
            'phone_number' => $request->phone_number,
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

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $token = Str::random(40);

            // Delete existing reset tokens
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Store new reset token (plain text)
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);

            // Send email
            $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

            Mail::send('verify', ['resetUrl' => $resetUrl], function ($message) use ($request) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($request->email)->subject('Reset Password Notification');
            });
            return back()->with('success', 'We have e-mailed your password reset link!');

        } catch (\Exception $e) {
            Log::error('Password Reset Email Error: '.$e->getMessage());
            return back()->with('error', 'Something went wrong while sending the reset email. Please try again.');
        }
    }
    
    public function showResetForm($token, Request $request)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        try {
            // Validate the request inputs
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|confirmed',
                'token' => 'required'
            ]);

            // Find password reset record
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
                return back()->with('error', 'Invalid token!')->withInput();
            }

            // Check if the token has expired (valid for 60 minutes)
            if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
                return back()->with('error', 'The password reset link has expired.')->withInput();
            }

            // Find the user and update the password
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

                // Delete the used password reset record
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                return redirect('/login')->with('success', 'Your password has been changed successfully!');
            }

            return back()->with('error', 'User not found!')->withInput();

        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while updating your password. Please try again later.');
        }
    }
}
