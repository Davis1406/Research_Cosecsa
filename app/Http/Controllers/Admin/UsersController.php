<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\Trainee;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::orderBy('id','desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        // Auto-create a linked Trainee record if the trainee role is assigned
        $this->ensureTraineeProfile($user);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        // Ensure a Trainee profile exists if the trainee role is (now) assigned
        $this->ensureTraineeProfile($user->fresh());

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Reset a user's password directly from the admin panel.
     */
    public function resetPassword(Request $request, User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'new_password'              => ['required', 'min:6', 'confirmed'],
            'new_password_confirmation' => ['required'],
        ]);

        $user->update(['password' => $request->new_password]);

        return back()->with('success', 'Password for ' . $user->name . ' has been reset successfully.');
    }

    /**
     * Create a Trainee record linked to $user if they have the trainee role
     * and one does not already exist.
     */
    protected function ensureTraineeProfile(User $user): void
    {
        $hasTraineeRole = $user->roles()
            ->where('slug', 'trainee')
            ->exists();

        if ($hasTraineeRole && !$user->trainee()->exists()) {
            Trainee::create([
                'name'    => $user->name,
                'email'   => $user->email,
                'user_id' => $user->id,
            ]);
        }
    }
}
