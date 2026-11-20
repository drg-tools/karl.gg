@extends('layouts.app')

@section('content')
    <x-auth-card eyebrow="Account" title="Choose a new password" lede="Use at least 8 characters. You'll be signed in once it's saved.">
        <form class="settings-form" method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="settings-field">
                <label class="browse-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p class="settings-field__error" id="email-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-field">
                <label class="browse-label" for="password">New password</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" autofocus @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p class="settings-field__error" id="password-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-field">
                <label class="browse-label" for="password-confirm">Confirm new password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
            </div>

            <div class="settings-form__foot">
                <button class="loadout-button" type="submit">Save password</button>
            </div>
        </form>
    </x-auth-card>
@endsection
