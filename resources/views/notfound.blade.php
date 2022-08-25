<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tidak Ditemukan</title>

    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Main Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="bg-white w-11/12 md:w-2/3 lg:w-1/2 xl:w-2/5 2xl:w-1/3 m-auto h-screen flex flex-col items-center justify-center p-4 lg:p-6">
        <img src="{{ asset('design/NotFound.svg') }}" class="w-full h-auto" alt="">
        @if (Auth::user()->role == "ADMIN")
            <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 text-primary hover:text-primary-70">
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>                  
                </span>
                <span class="text-lg xl:text-xl">Kembali ke Dashboard</span>
            </a>
        @else
            <a href="{{ route('classroom.index') }}" class="flex items-center gap-3 text-primary hover:text-primary-70">
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>                  
                </span>
                <span class="text-lg xl:text-xl">Kembali ke Beranda</span>
            </a>
            
        @endif
    </div>
</body>
</html>
