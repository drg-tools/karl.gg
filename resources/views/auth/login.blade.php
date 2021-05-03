@extends('layouts.app-v2')

@section('content')
    <div class="flex flex-wrap justify-center">
        <div class="w-full max-w-sm">
            <div class="flex flex-col break-words bg-gray-700 border border-2 border-gray-700 rounded shadow-md">

                <div class="font-semibold bg-gray-800 text-gray-300 py-3 px-6 mb-0">
                    {{ __('Login') }}
                </div>

                <form class="w-full p-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="flex flex-wrap mb-6">
                        <label for="email" class="block text-gray-300 text-sm font-bold mb-2">
                            {{ __('E-Mail Address') }}:
                        </label>

                        <input id="email" type="email" class="form-input w-full @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <p class="text-red-500 text-xs italic mt-4">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap mb-6">
                        <label for="password" class="block text-gray-300 text-sm font-bold mb-2">
                            {{ __('Password') }}:
                        </label>

                        <input id="password" type="password" class="form-input w-full @error('password') border-red-500 @enderror" name="password" required>

                        @error('password')
                            <p class="text-red-500 text-xs italic mt-4">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex mb-6">
                        <label class="inline-flex justify-center text-sm text-gray-300" for="remember">
                            <input type="checkbox" name="remember" id="remember" class="form-checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Remember Me') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-wrap justify-center">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-100 font-bold py-2 px-4 rounded focus:outline-none focus:ring">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-orange-500 hover:text-orange-700 whitespace-nowrap no-underline ml-auto" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <p class="w-full text-xs text-center text-gray-300 mt-8 -mb-4">
                                {{ __("Don't have an account?") }}
                                <a class="text-orange-500 hover:text-orange-700 no-underline" href="{{ route('register') }}">
                                    {{ __('Register') }}
                                </a>
                            </p>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
