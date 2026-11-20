@extends('layouts.app')

@section('content')
    <x-auth-card eyebrow="Account" title="Create an account" lede="Publish your builds, favorite others and join the discussion.">
        <form class="settings-form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="settings-field">
                <label class="browse-label" for="name">Display name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="nickname" autofocus @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                @error('name')
                    <p class="settings-field__error" id="name-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-field">
                <label class="browse-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p class="settings-field__error" id="email-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-field">
                <label class="browse-label" for="password">Password</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p class="settings-field__error" id="password-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-field">
                <label class="browse-label" for="password-confirm">Confirm password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
            </div>

            <div class="settings-form__foot">
                <button class="loadout-button" type="submit">Create account</button>
            </div>
        </form>

        <x-slot:footer>
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </x-slot:footer>
    </x-auth-card>
@endsection
