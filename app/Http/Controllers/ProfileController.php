<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Str;
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
        $validated = $request->validated();

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            if (! empty($request->user()->avatar) && Storage::disk('public')->exists($request->user()->avatar)) {
                Storage::disk('public')->delete($request->user()->avatar);
            }

            $validated['avatar'] = $this->storeSquareAvatar($request->file('avatar'));
        }

        $request->user()->update($validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    protected function storeSquareAvatar($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
        $filename = 'img/'.uniqid('avatar_', true).'.'.$extension;
        
        $path = $file->storeAs('img', basename($filename), 'public');
        
        $publicDirectory = public_path('img');
        if (! is_dir($publicDirectory)) {
            mkdir($publicDirectory, 0777, true);
        }
        copy(storage_path('app/public/' . $path), public_path($filename));

        return $filename;
    }

    public function upload(Request $request)
    {
        if ($request->avatar) {
            if (! empty($request->user()->avatar)) {
                Storage::disk('public')->delete($request->user()->avatar);
            }
            $newFileName = Str::after($request->avatar, 'tmp/');

            Storage::disk('public')->move($request->avatar, "img/$newFileName");

            $validated['avatar'] = "img//$newFileName";
        }

        if ($request->hasFile('avatar')) {
            // $path =
            $request->file('avatar')->store('img', 'public');

            // return $path;
        }

        // return response()->json(['error' => 'Gagal mengupload'], 400);
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
}