<?php
namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $speaker = auth()->user()->speaker;
        return view('facilitator.profile', compact('speaker'));
    }

    public function update(Request $request)
    {
        $user    = auth()->user();
        $speaker = $user->speaker;

        $rules = [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'description' => 'nullable|string|max:1000',
            'twitter'     => 'nullable|string|max:255',
            'linkedin'    => 'nullable|string|max:255',
            'facebook'    => 'nullable|string|max:255',
            'password'    => 'nullable|string|min:8|confirmed',
            'avatar'      => 'nullable|image|max:2048',
        ];
        $validated = $request->validate($rules);

        // Update user
        $userUpdate = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];
        if (!empty($validated['password'])) {
            $userUpdate['password'] = Hash::make($validated['password']);
        }
        $user->update($userUpdate);

        // Update speaker record if linked
        if ($speaker) {
            $speaker->update([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? $speaker->description,
                'twitter'     => $validated['twitter'] ?? null,
                'linkedin'    => $validated['linkedin'] ?? null,
                'facebook'    => $validated['facebook'] ?? null,
            ]);

            if ($request->hasFile('avatar')) {
                $speaker->clearMediaCollection('photo');
                $speaker->addMediaFromRequest('avatar')
                        ->toMediaCollection('photo');
            }
        }

        return back()->with('message', 'Profile updated successfully.');
    }
}
