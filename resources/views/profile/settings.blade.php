@extends('layouts.app')

@section('page-heading')
    <p>Settings</p>
@endsection

@php
    $statusMessages = [
        'profile-updated' => 'Profile saved.',
        'password-updated' => 'Password updated.',
    ];
    $profileErrors = $errors->updateProfile;
    $passwordErrors = $errors->updatePassword;
@endphp

@section('content')
    <div class="loadout-page settings">
        <header class="browse-header">
            <div>
                <p class="loadout-eyebrow">Account</p>
                <h1 class="browse-header__title">Settings</h1>
                <p class="browse-header__lede">Update how you appear on Karl.gg and keep your account secure.</p>
            </div>
            <a class="loadout-button is-secondary" href="{{ route('user.profile', $user->id) }}">View my builds <span aria-hidden="true">→</span></a>
        </header>

        @if (isset($statusMessages[session('status')]))
            <div class="loadout-notice" role="status">
                <strong>{{ $statusMessages[session('status')] }}</strong>
            </div>
        @endif

        <div class="settings-grid">
            <section class="loadout-panel settings-panel" aria-labelledby="settings-profile-title">
                <div class="settings-panel__head">
                    <h2 id="settings-profile-title">Profile</h2>
                    <p>Your name appears on your builds and profile. Your email stays private.</p>
                </div>

                <form class="settings-form" method="POST" action="{{ route('account.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="settings-field">
                        <label class="browse-label" for="name">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="nickname" @if ($profileErrors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif>
                        @if ($profileErrors->has('name'))
                            <p class="settings-field__error" id="name-error">{{ $profileErrors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="settings-field">
                        <label class="browse-label" for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email" @if ($profileErrors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
                        @if ($profileErrors->has('email'))
                            <p class="settings-field__error" id="email-error">{{ $profileErrors->first('email') }}</p>
                        @endif
                    </div>

                    <div class="settings-form__foot">
                        <button class="loadout-button" type="submit">Save profile</button>
                    </div>
                </form>
            </section>

            <section class="loadout-panel settings-panel" aria-labelledby="settings-password-title">
                <div class="settings-panel__head">
                    <h2 id="settings-password-title">Password</h2>
                    <p>Confirm your current password, then choose a new one with at least 8 characters.</p>
                </div>

                <form class="settings-form" method="POST" action="{{ route('account.password.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="text" name="username" value="{{ $user->email }}" autocomplete="username" hidden>

                    <div class="settings-field">
                        <label class="browse-label" for="current_password">Current password</label>
                        <input id="current_password" type="password" name="current_password" required autocomplete="current-password" @if ($passwordErrors->has('current_password')) aria-invalid="true" aria-describedby="current-password-error" @endif>
                        @if ($passwordErrors->has('current_password'))
                            <p class="settings-field__error" id="current-password-error">{{ $passwordErrors->first('current_password') }}</p>
                        @endif
                    </div>

                    <div class="settings-field">
                        <label class="browse-label" for="password">New password</label>
                        <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" @if ($passwordErrors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                        @if ($passwordErrors->has('password'))
                            <p class="settings-field__error" id="password-error">{{ $passwordErrors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="settings-field">
                        <label class="browse-label" for="password_confirmation">Confirm new password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
                    </div>

                    <div class="settings-form__foot">
                        <button class="loadout-button" type="submit">Update password</button>
                        @if (Route::has('password.request'))
                            <a class="browse-clear" href="{{ route('password.request') }}">Forgot your password?</a>
                        @endif
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
