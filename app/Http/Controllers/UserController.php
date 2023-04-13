<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "prefixname" => "string|nullable",
            "firstname" => "required",
            "middlename" => "string|nullable",
            "lastname" => "required",
            "sufixname" => "string|nullable",
            "username" => "required|max:255|unique:users",
            "email_address" => "required|email|max:255|unique:users,email",
            "password" => "required|confirmed",
            "photo" => "max:10240",
        ]);

        $photo = null;
        if ($request->has('photo')) {
            $photoFile = $validated['photo'];
            $photoExt = $photoFile->getClientOriginalExtension();
            $uniqueFilename = Str::random(60);
            $directory = 'public/avatars';

            $photo = $validated['photo']->storeAs($directory . '/' . $uniqueFilename . '.' . $photoExt);
        }

        $user = User::create([
            'prefixname' => $validated['prefixname'],
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'],
            'lastname' => $validated['lastname'],
            'suffixname' => $validated['sufixname'],
            'username' => $validated['username'],
            'email' => $validated['email_address'],
            'password' => $validated['password'],
            'photo' => $photo,
            'type' => 'user'
        ]);

        return redirect()
            ->route('users.show', $user)
            ->with('successNotification', 'A new user is created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "prefixname" => "string|nullable",
            "firstname" => "required",
            "middlename" => "string|nullable",
            "lastname" => "required",
            "sufixname" => "string|nullable",
            "username" => ['required', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)], //"required|max:255|unique:users,{$user->id}",
            "email_address" => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)], //"required|email|max:255|unique:users,email,{$user->id}",
            "photo" => "max:10240",
        ]);

        $photo = $user->photo;
        if ($request->has('photo')) {
            $photoFile = $validated['photo'];
            $photoExt = $photoFile->getClientOriginalExtension();
            $uniqueFilename = Str::random(60);
            $directory = 'public/avatars';

            $photo = $validated['photo']->storeAs($directory . '/' . $uniqueFilename . '.' . $photoExt);
        }

        $user->update([
            'prefixname' => $validated['prefixname'],
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'],
            'lastname' => $validated['lastname'],
            'suffixname' => $validated['sufixname'],
            'username' => $validated['username'],
            'email' => $validated['email_address'],
            'photo' => $photo,
            'type' => 'user'
        ]);

        return back()
            ->with('successNotification', 'A user is updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('successNotification', 'A user record has been deleted.');
    }

    /**
     * Display all trashed users
     */
    public function trashed()
    {
        $users = User::onlyTrashed()
            ->paginate(10);

        return view('users.trashed', compact('users'));
    }

    /**
     * Delete user's record permanently
     */
    public function delete($record)
    {
        User::onlyTrashed()
            ->findOrFail($record)
            ->forceDelete();

        return redirect()
            ->route('users.trashed')
            ->with('successNotification', 'User record has been permanently deleted');
    }

    /**
     * Restore a soft deleted user
     */
    public function restore($record)
    {
        User::onlyTrashed()
            ->findOrFail($record)
            ->restore();

        return redirect()
            ->route('users.trashed')
            ->with('successNotification', 'User record has been restored');
    }
}
