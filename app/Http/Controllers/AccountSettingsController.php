<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountSettingsController extends Controller
{
    public function edit(Request $request): View
    {
        SEOTools::setTitle('Account settings');
        SEOTools::metatags()->setRobots('noindex, nofollow');

        return view('profile.settings', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('updateProfile', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill($validated)->save();

        return redirect()->route('account.settings')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->forceFill(['password' => Hash::make($validated['password'])])->save();

        return redirect()->route('account.settings')->with('status', 'password-updated');
    }
}
