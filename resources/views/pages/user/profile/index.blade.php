@extends('layouts.master')

@section('content')

<div class="w-full xl:pt-5 xl:pr-5">

    @include('components.session-alert')

    <div class="w-full flex items-center justify-between mb-4 xl:mb-6">
        <p class="block m-0 text-lg xl:text-xl font-bold text-primary tracking-wider">
            Profil Pengguna
        </p>
        <a href="{{ route('profile.edit') }}" class="block m-0 py-1.5 px-4 bg-primary text-white rounded-md font-medium">
            Edit Profil
        </a>
    </div>

    <div class="bg-white rounded-md xl:rounded-lg overflow-hidden drop-shadow p-4 md:p-6 lg:p-10 xl:p-16 w-full flex flex-col lg:flex-row lg:items-center gap-4 md:gap-6 lg:gap-10 xl:gap-16">
        <div class="w-56 m-auto lg:m-0 lg:flex lg:flex-col lg:items-center lg:justify-center">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : asset('image/PP.jpg') }}" class="w-full h-auto rounded-full" alt="FOTO PROFIL">
        </div>
        <div class="w-fit px-4 lg:p-0">
            <p class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">Nama Lengkap</p>
            <p class="block m-0 mb-3 text-gray-700 tracking-wide text-lg xl:text-xl">{{ Auth::user()->name }}</p>
            <p class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">Alamat Email</p>
            <p class="block m-0 mb-1 text-gray-700 tracking-wide text-lg xl:text-xl">{{ Auth::user()->email }}</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        
    </script>
@endsection