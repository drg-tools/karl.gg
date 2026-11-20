<?php

namespace App\Http\Controllers;

use App\DeleteAccount;
use App\Post;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    public function destroy(Request $request, DeleteAccount $deleteAccount): RedirectResponse
    {
        $request->validateWithBag('deleteAccount', [
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if (Post::where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'delete_password' => 'Accounts that have written blog posts must be deleted by an administrator. Email admin@karl.gg.',
            ])->errorBag('deleteAccount');
        }

        Auth::logout();
        $deleteAccount->handle($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'account-deleted');
    }
}
