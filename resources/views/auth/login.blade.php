@extends('layouts.guest')

@section('content')
    <div class="w-full md:w-3/4 lg:w-2/3 xl:w-1/4 p-4 md:p-3 xl:p-2">
        <p class="text-lg text-primary font-bold  px-2">Selamat Datang</p>
        <img src="{{ asset('design/Reading_Book.png') }}" alt="" class="w-3/4 h-auto mx-auto my-3">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-4 px-2">
                <label for="email" class="block mb-2 text-base font-medium text-gray-900">{{ __('Email') }}</label>
                <input type="email" id="email" name="email"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5" 
                    value="{{ old('email') }}" required autocomplete="email" placeholder="student@school.com">
                {{-- @error('email') is-invalid @enderror --}}
                {{-- @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror --}}
            </div>

            <!-- PASSWORD -->
            <div class="mb-6 px-2">
                <label for="password" class="block mb-2 text-base font-medium text-gray-900">{{ __('Password') }}</label>
                <input type="password" id="password" name="password"
                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5" required autocomplete="current-password">
                {{-- @error('email') is-invalid @enderror --}}
                {{-- @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror --}}
            </div>

            <!-- REMEMBER -->
            {{-- <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300" required="">
                </div>
                <label for="remember" class="ml-2 text-sm font-medium text-gray-900">{{ __('Remember Me') }}</label>
            </div> --}}

            <div class="w-full px-2">
                <button type="submit" class="text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                    {{ __('Masuk') }}
                </button>
            </div>

            {{-- @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif --}}
        </form>
    </div>
@endsection
