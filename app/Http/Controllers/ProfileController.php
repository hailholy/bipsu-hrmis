<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        try {
            // Delete old photo if exists
            if (auth()->user()->profile_photo_path) {
                Storage::disk('public')->delete(auth()->user()->profile_photo_path);
            }

            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Update user record
            auth()->user()->update(['profile_photo_path' => $path]);

            return back()->with('success', 'Profile photo updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile photo: '.$e->getMessage());
        }
    }

    public function deleteProfilePhoto(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Delete the photo file from storage if it exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Clear the profile photo path in the database
            $user->update(['profile_photo_path' => null]);
            
            return back()->with('success', 'Profile photo removed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove profile photo: '.$e->getMessage());
        }
    }
}