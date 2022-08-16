@extends('layouts.master')

@section('content')

<div class="w-full">

    @include('components.session-alert')

    <div class="w-full flex items-center justify-between mb-4 xl:mb-6">
        <a href="{{ route('profile.index') }}" class="flex items-center gap-2 text-primary hover:text-primary-70">
            <span class="block m-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </span>
            <span class="text-lg xl:text-xl">Kembali</span>
        </a>
        <p class="block m-0 text-lg xl:text-xl font-bold text-primary tracking-wider">
            Edit Profil
        </p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf @method('POST')
        <div class="bg-white rounded-md xl:rounded-lg overflow-hidden drop-shadow p-4 md:p-6 lg:p-10 xl:p-16 w-full flex flex-col lg:flex-row lg:items-center gap-4 md:gap-6 lg:gap-10 xl:gap-16">
            <div class="w-56 m-auto lg:m-0 lg:flex lg:flex-col lg:items-center lg:justify-center">
                <img src="{{ asset('image/PP.jpg') }}" class="w-full h-auto rounded-full" alt="FOTO PROFIL">
            </div>
            <div class="w-fit px-4 lg:p-0">
                <!-- FULL NAME -->
                <label for="name" class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Nama Lengkap
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') ? old('name') : Auth::user()->name }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('name') mb-0 @else mb-4 @enderror" 
                    autocomplete="off" placeholder="Nama Lengkap">
                @error('name')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror

                <!-- EMAIL ADDRESS -->
                <label for="email" class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Alamat Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') ? old('email') : Auth::user()->email }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('email') mb-0 @else mb-4 @enderror" 
                    autocomplete="off" placeholder="Alamat Email">
                @error('email')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
                    
                <!-- OLD PASSWORD -->
                <label for="old_password" class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Kata Sandi Lama
                </label>
                <input type="password" id="old_password" name="old_password" value="{{ old('old_password') }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('old_password') mb-0 @else mb-4 @enderror" 
                    autocomplete="off" placeholder="Kata Sandi Lama">
                @error('old_password')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
                
                <!-- NEW PASSWORD -->
                <label for="new_password" class="block m-0 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Kata Sandi Baru
                </label>
                <small class="text-gray-500 block m-0 mb-1 font-bold">Opsional</small>
                <input type="password" id="new_password" name="new_password" value="{{ old('new_password') }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('new_password') mb-0 @else mb-4 @enderror" 
                    autocomplete="off" placeholder="Kata Sandi Baru">
                @error('new_password')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
                
                <!-- PASSWORD CONFIRMATION -->
                <label for="password" class="block m-0 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Konfirmasi Kata Sandi Baru
                </label>
                <small class="text-gray-500 block m-0 mb-1 font-bold">Opsional</small>
                <input type="password" id="password_confirmation" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('new_password_confirmation') mb-0 @else mb-6 lg:mb-0 @enderror" 
                    autocomplete="off" placeholder="Konfirmasi Kata Sandi Baru">
                @error('new_password_confirmation')
                    <p class="block mt-1 mb-6 lg:mb-0 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div>
            <button type="submit">SIMPAN PERUBAHAN</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        
    </script>
@endsection
{{-- @php
    $encrypted_password = Auth::user()->password;
    $decrypted_password = Illuminate\Support\Facades\Crypt::decryptString($encrypted_password);
@endphp
{{ $decrypted_password }} --}}