<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.min'       => 'Your new password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = auth()->user();
        $user->password              = Hash::make($request->password);
        $user->must_change_password  = false;
        $user->save();

        return redirect('/home')->with('status', 'Password updated successfully. Welcome!');
    }
}
