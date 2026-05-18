<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Speaker;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FacilitatorsController extends Controller
{
    public function index()
    {
        $facilitators = User::with('roles', 'speaker')
            ->whereHas('roles', fn($q) => $q->whereIn('title', ['Facilitator', 'Lead Facilitator']))
            ->latest()
            ->get();
        return view('facilitator.facilitators.index', compact('facilitators'));
    }

    public function create()
    {
        $roles = Role::whereIn('title', ['Facilitator', 'Lead Facilitator'])->get();
        return view('facilitator.facilitators.form', [
            'facilitator' => null,
            'roles'       => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'role_id'     => 'required|exists:roles,id',
            'description' => 'nullable|string|max:1000',
            'twitter'     => 'nullable|string|max:255',
            'linkedin'    => 'nullable|string|max:255',
            'facebook'    => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->roles()->attach($validated['role_id']);

        Speaker::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'twitter'     => $validated['twitter'] ?? null,
            'linkedin'    => $validated['linkedin'] ?? null,
            'facebook'    => $validated['facebook'] ?? null,
            'user_id'     => $user->id,
        ]);

        return redirect()->route('facilitator.facilitators.index')
                         ->with('message', 'Facilitator account created successfully.');
    }

    public function edit($id)
    {
        $facilitator = User::with('roles', 'speaker')
            ->whereHas('roles', fn($q) => $q->whereIn('title', ['Facilitator', 'Lead Facilitator']))
            ->findOrFail($id);
        $roles = Role::whereIn('title', ['Facilitator', 'Lead Facilitator'])->get();
        return view('facilitator.facilitators.form', compact('facilitator', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $facilitator = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $facilitator->id,
            'role_id'     => 'required|exists:roles,id',
            'password'    => 'nullable|string|min:8|confirmed',
            'description' => 'nullable|string|max:1000',
            'twitter'     => 'nullable|string|max:255',
            'linkedin'    => 'nullable|string|max:255',
            'facebook'    => 'nullable|string|max:255',
        ]);

        $userUpdate = ['name' => $validated['name'], 'email' => $validated['email']];
        if (!empty($validated['password'])) {
            $userUpdate['password'] = Hash::make($validated['password']);
        }
        $facilitator->update($userUpdate);
        $facilitator->roles()->sync([$validated['role_id']]);

        $speakerData = [
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'twitter'     => $validated['twitter'] ?? null,
            'linkedin'    => $validated['linkedin'] ?? null,
            'facebook'    => $validated['facebook'] ?? null,
        ];

        if ($facilitator->speaker) {
            $facilitator->speaker->update($speakerData);
        } else {
            $speakerData['user_id'] = $facilitator->id;
            Speaker::create($speakerData);
        }

        return redirect()->route('facilitator.facilitators.index')
                         ->with('message', 'Facilitator updated successfully.');
    }

    public function destroy($id)
    {
        if ((int)$id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $facilitator = User::findOrFail($id);
        $facilitator->delete();
        return redirect()->route('facilitator.facilitators.index')->with('message', 'Facilitator removed.');
    }
}
