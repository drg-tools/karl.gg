@extends('layouts.app')

@section('content')
    <x-auth-card eyebrow="Account" title="Sign in" lede="Welcome back, miner. Sign in to save builds and favorites.">
        <form class="settings-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="settings-field">
                <label class="browse-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p class="settings-field__error" id="email-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-field">
                <div class="auth-field-row">
                    <label class="browse-label" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="browse-clear" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p class="settings-field__error" id="password-error">{{ $message }}</p>
                @enderror
            </div>

            <label class="auth-remember" for="remember">
                <input type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                Remember me
            </label>

            <div class="settings-form__foot">
                <button class="loadout-button" type="submit">Sign in</button>
            </div>
        </form>

        @if (Route::has('register'))
            <x-slot:footer>
                New to Karl.gg? <a href="{{ route('register') }}">Create an account</a>
            </x-slot:footer>
        @endif
    </x-auth-card>
@endsection
