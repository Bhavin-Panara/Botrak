<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function LoginAuth(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.'
        ]);

        $user = User::with('roles')->where('email', $request->email)->first();

        $valid_role = $user->roles->contains('name', 'super_admin');

        if (!$user) {
            return redirect()->route('login.show')->withErrors(['email' => 'We can\'t find a user with that email address.'])->withInput();
        }
    
        if (!Hash::check($request->password, $user->encrypted_password)) {
            return redirect()->route('login.show')->withErrors(['password' => 'The password you\'ve entered is incorrect.'])->withInput();
        }

        // if (!$valid_role) {
        //     return redirect()->route('login.show')->with('error', 'Only Super Admin can logged in.');
        // }

        // if ($user && Hash::check($request->password, $user->encrypted_password) && $valid_role) {
        if ($user && Hash::check($request->password, $user->encrypted_password)) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'You have been logged in successfully.');
        }

        return redirect()->route('login.show')->withErrors(['email' => 'We can\'t find a user with this credentials.'])->withInput();
    }
}