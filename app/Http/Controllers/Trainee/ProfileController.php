<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Trainee;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $trainee = auth()->user()->trainee;
        return view('trainee.profile', compact('trainee'));
    }

    public function update(Request $request)
    {
        $trainee = auth()->user()->trainee;
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'phone'               => 'nullable|string|max:50',
            'institution'         => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'country'             => 'nullable|string|max:100',
            'specialty'           => 'nullable|string|max:255',
        ]);
        $trainee->update($validated);
        // Also update user name
        auth()->user()->update(['name' => $validated['name']]);
        return back()->with('message', 'Profile updated successfully.');
    }
}
