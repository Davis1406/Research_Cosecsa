<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySpeakerRequest;
use App\Http\Requests\StoreSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Speaker;
use App\User;
use App\Role;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class SpeakersController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('speaker_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speakers = Speaker::orderBy('id','desc')->get();

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create()
    {
        abort_if(Gate::denies('speaker_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::orderBy('title')->get();
        return view('admin.speakers.create', compact('roles'));
    }

    public function store(StoreSpeakerRequest $request)
    {
        $speaker = Speaker::create($request->all());

        if ($request->input('photo', false)) {
            $speaker->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
        }

        // Optionally create a portal user account
        if ($request->filled('portal_email') && $request->filled('portal_password') && $request->filled('portal_role_id')) {
            $user = User::create([
                'name'     => $speaker->name,
                'email'    => $request->portal_email,
                'password' => Hash::make($request->portal_password),
            ]);
            $user->roles()->attach($request->portal_role_id);
            $speaker->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.speakers.index')->with('message', 'Facilitator created successfully.');
    }

    public function edit(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::orderBy('title')->get();
        $speaker->load('user.roles');
        return view('admin.speakers.edit', compact('speaker', 'roles'));
    }

    public function update(UpdateSpeakerRequest $request, Speaker $speaker)
    {
        $speaker->update($request->all());

        if ($request->input('photo', false)) {
            if (!$speaker->photo || $request->input('photo') !== $speaker->photo->file_name) {
                $speaker->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
            }
        } elseif ($speaker->photo) {
            $speaker->photo->delete();
        }

        // Update or create portal user account
        if ($request->filled('portal_email') && $request->filled('portal_role_id')) {
            $user = $speaker->user;
            if ($user) {
                // Update existing account
                $userUpdate = ['name' => $speaker->name, 'email' => $request->portal_email];
                if ($request->filled('portal_password')) {
                    $userUpdate['password'] = Hash::make($request->portal_password);
                }
                $user->update($userUpdate);
                $user->roles()->sync([$request->portal_role_id]);
            } elseif ($request->filled('portal_password')) {
                // Create new account
                $user = User::create([
                    'name'     => $speaker->name,
                    'email'    => $request->portal_email,
                    'password' => Hash::make($request->portal_password),
                ]);
                $user->roles()->attach($request->portal_role_id);
                $speaker->update(['user_id' => $user->id]);
            }
        }

        return redirect()->route('admin.speakers.index')->with('message', 'Facilitator updated successfully.');
    }

    public function show(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.speakers.show', compact('speaker'));
    }

    public function destroy(Speaker $speaker)
    {
        abort_if(Gate::denies('speaker_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker->delete();

        return back();
    }

    public function massDestroy(MassDestroySpeakerRequest $request)
    {
        Speaker::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
