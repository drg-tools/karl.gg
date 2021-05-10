@extends('layouts.app')

@section('content')
    <div class="container mx-auto loginPage">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">

                @if (session('status'))
                    <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="flex flex-col break-words bg-gray-700 border border-2 border-gray-700 rounded shadow-md">

                    <div class="font-semibold bg-gray-800 text-gray-300 py-3 px-6 mb-0">
                        {{ __('Reset Password') }}
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('password.email') }}">
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

                        <div class="flex flex-wrap">
                            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-100 font-bold py-2 px-4 rounded focus:outline-none focus:ring">
                                {{ __('Send Password Reset Link') }}
                            </button>

                            <p class="w-full text-xs text-center text-gray-300 mt-8 -mb-4">
                                <a class="text-orange-500 hover:text-orange-700 no-underline" href="{{ route('login') }}">
                                    {{ __('Back to login') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
