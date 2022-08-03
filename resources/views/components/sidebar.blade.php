<div class="relative w-full h-full overflow-y-auto custom-scrollbar p-3 pb-8 flex flex-col gap-4 ">
    <!-- LOGO FOR SMALL SCREEN -->
    <a href="" class="lg:hidden flex gap-2 items-center py-3">
        <img src="{{ asset('image/logo_tut_wuri.png') }}" class="w-9 h-9" alt="">
        <p class="text-base font-medium ">SMAN 123 RAJAWALI</p>
    </a>
    
    <!-- HOME -->
    <a href="{{ route('classroom.index') }}" 
        class="{{ request()->routeIs('classroom.index') ? 'bg-primary-70 font-semibold' : 'bg-primary' }} text-white p-3 rounded-md" 
        x-bind:class="showSidebar ? 'w-full' : 'w-fit' ">
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
                <a href="{{ route('classroom.show', $classroom->classroom_id) }}" class="text-primary-90 {{ request()->is('mata-pelajaran/'.$classroom->classroom_id) ? 'font-semibold' : 'font-thin' }} py-1">
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
</div>