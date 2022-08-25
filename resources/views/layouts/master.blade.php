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
    @yield('styles')
</head>
<body
    x-data="{
        showSidebar     : false,
        showAuthMenu    : false,
        showSearchInput : false,
        showNotifMenu   : false,
    }">
    <nav class="fixed z-[999] top-0 w-full flex justify-between p-4 lg:px-6 bg-primary-50 text-white">

        <!-- LOGO AND SIDEBAR TOGGLER -->
        <div class="flex justify-between items-center w-[256px]">
            <a href="" class="gap-2 items-center "
                x-cloak 
                x-bind:class="showSidebar ? 'hidden lg:flex' : 'hidden'"
                x-transition:enter.duration.400ms
                x-transition:leave.duration.400ms>
                <img src="{{ asset('image/logo_tut_wuri.png') }}" class="w-9 h-9" alt="LOGO">
                <p class="text-base font-medium ">SMAN 123 RAJAWALI</p>
            </a>
            <button type="button" class="block" x-on:click="showSidebar =! showSidebar">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </span>
            </button>
        </div>

        <!-- HEADER ACTION GROUP SECTION -->
        <div class="flex gap-4 items-center">
            <!-- SEARCH INPUT -->
            <div class="hidden md:block w-64 lg:w-72 relative ">
                <input class="block border border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 w-full p-1.5 pl-8 custom-placeholder" 
                    type="text" name="search" id="search" placeholder="Cari">
                <span class="absolute top-0 left-0 z-[1000] w-8 p-1.5 aspect-square flex items-center text-primary-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
            
            <!-- USER NOTIFICATION -->
            <div class="relative">
                <button class="flex items-center">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- USER AUTH ACTIONS DROPDOWN -->
            @include('components.user-dropdown')
        </div>
    </nav>

    <main class="relative w-full">
        <!-- SIDEBAR -->
        <aside class="bg-primary-10 fixed z-[1000] top-[4.25rem] h-[calc(100vh-4.25rem)]"
            x-cloak
            x-bind:class="showSidebar ? 'left-0 w-[288px] lg:w-[304px]' : 'right-full lg:left-0 w-fit ' "
            x-transition:enter.duration.400ms
            x-transition:leave.duration.400ms
            x-data="{
                openSubjectCollapse     : false,
                openDataMasterCollapse  : false
            }">

            @include('components.sidebar')

            {{-- <div class="relative w-full h-full overflow-y-auto custom-scrollbar p-3 pb-8 flex flex-col gap-4 ">
                <!-- LOGO FOR SMALL SCREEN -->
                <a href="" class="lg:hidden flex gap-2 items-center py-3">
                    <img src="{{ asset('image/logo_tut_wuri.png') }}" class="w-9 h-9" alt="">
                    <p class="text-base font-medium ">SMAN 123 RAJAWALI</p>
                </a>
                
                <!-- HOME -->
                <a href="" class="text-white bg-primary p-3 rounded-md" x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                    <div class="flex gap-3 items-center" x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </span>
                        <span x-bind:class="showSidebar ? 'inline-block' : 'hidden' ">
                            Beranda
                        </span>
                    </div>
                </a>

                @if (Auth::user()->role != "ADMIN")
                    <!-- SUBJECTS COLLAPSE BUTTON -->
                    <button x-on:click="openSubjectCollapse =! openSubjectCollapse" class="flex justify-between items-center text-white bg-primary p-3 rounded-md"  x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                        <div class="flex gap-3 items-center" x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </span>
                            <span x-bind:class="showSidebar ? 'inline-block' : 'hidden' ">
                                Mata Pelajaran
                            </span>
                        </div>
                        <span x-bind:class="showSidebar ? 'inline-block' : 'hidden' ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" x-bind:class="openSubjectCollapse && 'rotate-90' " viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    <!-- SUBJECTS COLLAPSE MENU -->
                    <div class="flex flex-col gap-3 px-3"
                        x-cloak x-collapse 
                        x-show="openSubjectCollapse && showSidebar"
                        x-transition:enter.duration.700ms
                        x-transition:leave.duration.350ms
                        >
                        @forelse ($user_classrooms as $classroom)
                            <a href="" class="text-primary-90 font-thin py-1">
                                {{ $classroom->name }}
                            </a>
                        @empty
                            <p class="text-primary-90 font-thin py-1">
                                Belum ada kelas
                            </p>
                        @endforelse
                    </div>

                    <!-- USER PROFILE -->
                    <a href="" class="text-white bg-primary p-3 rounded-md" x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                        <div class="flex gap-3 items-center" x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span x-bind:class="showSidebar ? 'inline-block' : 'hidden'">
                                Profil Saya
                            </span>
                        </div>
                    </a>
                @else
                    <!-- DATA MASTER COLLAPSE BUTTON FOR ADMIN -->
                    <button x-on:click="openDataMasterCollapse =! openDataMasterCollapse" 
                        class="flex justify-between items-center text-white bg-primary p-3 rounded-md" 
                        x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                        <div class="flex gap-3 items-center" x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </span>
                            <span x-bind:class="showSidebar ? 'inline-block' : 'hidden' ">
                                Data Master
                            </span>
                        </div>
                        <span x-bind:class="showSidebar ? 'inline-block' : 'hidden' ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" x-bind:class="openDataMasterCollapse && 'rotate-90'" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    <!-- DATA MASTER COLLAPSE MENU FOR ADMIN -->
                    <div class="flex flex-col gap-3 px-3"
                        x-cloak x-collapse
                        x-show="openDataMasterCollapse && showSidebar" 
                        x-transition:enter.duration.300ms
                        x-transition:leave.duration.300ms
                        >
                        <a href="" class="text-primary-90 font-thin py-1">
                            Data Akun
                        </a>
                        <a href="" class="text-primary-90 font-thin py-1">
                            Data Kelas
                        </a>
                    </div>
                @endif
            </div> --}}
            
        </aside>
        
        <div class="absolute w-full bg-white top-[4.25rem] right-0 min-h-[calc(100vh-4.25rem)] p-4"
            x-bind:class="showSidebar ? 'lg:w-[calc(100vw-321px)]' : 'lg:w-[calc(100vw-4.25rem-25px)]' "
            >
            {{-- @yield('alertMsgContainer') --}}
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/flowbite.js') }}"></script>
    <script src="{{ asset('js/jQuery.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
