<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserService $userService)
    {
        return view('users.index', [
            'users' => $userService->list()
        ]);
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
    public function store(UserRequest $request, UserService $userService)
    {
        $validated = $request->validated();

        $photo = null;
        if ($request->has('photo')) {
            $photo = $userService->upload($validated['photo']);
        }

        $validated['photo'] = $photo;
        $user = $userService->store($validated);

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
    public function update(UserRequest $request, User $user, UserService $userService)
    {
        $validated = $request->validated();

        if (!Hash::check($validated['old_password'], $request->user()->password)) {
            return back()
                ->withInput()
                ->with('dangerNotification', 'Please enter your correct old password to update your password');
        }

        $photo = null;
        if ($request->has('photo')) {
            $photo = $userService->upload($validated['photo']);
        }

        $validated['photo'] = $photo;
        $user = $userService->update($user->id, $validated);

        return back()
            ->with('successNotification', 'A user is updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserService $userService)
    {
        $userService->deactivate($user->id);

        return redirect()
            ->route('users.index')
            ->with('successNotification', 'A user record has been deleted.');
    }

    /**
     * Display all trashed users
     */
    public function trashed(UserService $userService)
    {
        return view('users.trashed', [
            'users' => $userService->listTrashed()
        ]);
    }

    /**
     * Delete user's record permanently
     */
    public function delete($record, UserService $userService)
    {
        $userService->delete($record);

        return redirect()
            ->route('users.trashed')
            ->with('successNotification', 'User record has been permanently deleted');
    }

    /**
     * Restore a soft deleted user
     */
    public function restore($record, UserService $userService)
    {
        $userService->restore($record);

        return redirect()
            ->route('users.trashed')
            ->with('successNotification', 'User record has been restored');
    }
}
