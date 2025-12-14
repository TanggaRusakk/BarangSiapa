<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $user = $request->user();

        // Delete old photo if exists (uses `image_path` column)
        if ($user->image_path && file_exists(public_path($user->image_path))) {
            @unlink(public_path($user->image_path));
        }

        // Store new photo under public/images/profiles
        $file = $request->file('photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/profiles'), $filename);

        // Save relative path to DB using existing `image_path` column
        $user->image_path = 'images/profiles/' . $filename;
        $user->save();

        return response()->json([
            'success' => true,
            'photo_url' => asset($user->image_path)
        ]);
    }

    /**
     * Remove profile photo.
     */
    public function removePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Delete photo file if exists (uses `image_path` column)
        if ($user->image_path && file_exists(public_path($user->image_path))) {
            @unlink(public_path($user->image_path));
        }

        $user->image_path = null;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'photo-removed');
    }
}
