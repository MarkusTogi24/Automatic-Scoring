<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Main Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="fixed z-[999] top-0 w-full flex justify-between p-4 bg-primary-50 text-white">

        {{-- <div class="flex justify-between items-center border border-black w-[256px]"> --}}
        <div class="flex justify-between items-center  w-[256px]">
            {{-- <a href="" class="flex gap-2 items-center border border-black"> --}}
            <a href="" class="hidden lg:flex gap-2 items-center ">
                <img src="{{ asset('image/logo_tut_wuri.png') }}" class="w-9 h-9" alt="">
                {{-- <p class="text-base font-medium border border-black">SMAN 123 RAJAWALI</p> --}}
                <p class="text-base font-medium ">SMAN 123 RAJAWALI</p>
            </a>
            {{-- <button class="block border border-black"> --}}
            <button class="block ">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </span>
            </button>
        </div>

        <div class="flex gap-4 items-center">
            {{-- <div class="w-72 relative border border-black"> --}}
            <div class="hidden md:block w-64 lg:w-72 relative ">
                <input class="block border border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 w-full p-1.5 pl-8 custom-placeholder" 
                    type="text" name="search" id="search" placeholder="Cari">
                <span class="absolute top-0 left-0 z-[1000] w-8 p-1.5 aspect-square flex items-center text-primary-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
            
            <!-- NOTICATION -->
            {{-- <div class="relative border border-black"> --}}
            <div class="relative ">
                {{-- <button class="flex items-center border border-black"> --}}
                <button class="flex items-center ">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </span>
                </button>
            </div>
            {{-- <button class="flex items-center gap-2 border border-black"> --}}
            <button class="flex items-center gap-2 ">
                <img src="{{ asset('image/dummy_pp.png') }}" class="w-9 h-9" alt="">
                <p class="hidden lg:inline-block text-sm">{{ Auth::user()->name }}</p>
                <span class="hidden lg:inline-block ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </button>
            {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div> --}}
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    @yield('scripts')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
