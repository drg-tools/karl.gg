@extends('layouts.app')

@section('content')
    <x-auth-card eyebrow="Account" title="Reset password" lede="Enter your account email and we'll send you a link to choose a new password.">
        @if (session('status'))
            <div class="loadout-notice" role="status">
                <strong>{{ session('status') }}</strong>
            </div>
        @endif

        <form class="settings-form" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="settings-field">
                <label class="browse-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p class="settings-field__error" id="email-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-form__foot">
                <button class="loadout-button" type="submit">Send reset link</button>
            </div>
        </form>

        <x-slot:footer>
            Remembered it? <a href="{{ route('login') }}">Back to sign in</a>
        </x-slot:footer>
    </x-auth-card>
@endsection
