@extends('layouts.app')

@section('content')
    <x-auth-card eyebrow="Account" title="Confirm password" lede="This is a secure area. Confirm your password before continuing.">
        <form class="settings-form" method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="settings-field">
                <div class="auth-field-row">
                    <label class="browse-label" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="browse-clear" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" autofocus @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p class="settings-field__error" id="password-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-form__foot">
                <button class="loadout-button" type="submit">Confirm</button>
            </div>
        </form>
    </x-auth-card>
@endsection
