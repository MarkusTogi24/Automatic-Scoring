<div class="relative">
    <!-- USER AUTH ACTIONS DROPDOWN BUTTON -->
    <button class="flex items-center gap-2"
        x-on:click="showAuthMenu =! showAuthMenu"
    >
        <img src="{{ asset('image/PP.jpg') }}" class="rounded-full w-9 h-9" alt="">
        <p class="hidden lg:inline-block text-sm">{{ explode(" ",Auth::user()->name)[0] }}</p>
        <span class="hidden lg:inline-block" x-bind:class=" showAuthMenu && 'rotate-180' ">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    <!-- USER AUTH ACTIONS DROPDOWN MENU -->
    <div class="absolute top-full mt-4 right-0 bg-white z-[1000] text-gray-700 w-40 flex flex-col gap-2.5 py-2 rounded-b-md drop-shadow-md"
        x-cloak x-show="showAuthMenu"
        x-on:click.outside="showAuthMenu = false"
        x-transition:enter.duration.300ms
        x-transition:leave.duration.300ms>
        <div class="px-3 lg:hidden">
            <p class="text-sm text-primary-80 font-semibold">{{ explode(" ",Auth::user()->name)[0] }}</p>
        </div>

        <!-- SEARCH INPUT TRIGGER -->
        <div class="px-3 lg:hidden">
            <button type="button" class="text-sm flex gap-2 items-center w-full py-1 px-2 rounded border-2 border-primary-10 text-primary-50 bg-primary-10"
                x-on:click="
                    showAuthMenu = false,
                    showSearchInput = true
                ">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <span class="font-semibold">
                    {{ __('Cari') }}
                </span>
            </button>
        </div>
        
        <!-- LINK TO PROFILE PAGE -->
        <div class="px-3">
            <a href="{{ route('profile.index') }}" role="button" class="text-sm flex gap-2 items-center py-1 px-2 rounded border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="font-semibold">
                    {{ __('Profil') }}
                </span>
            </a>
        </div>

        <div class="border-t-2 border-gray-200"></div>

        <!-- LOGOUT BUTTON -->
        <div class="px-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm flex gap-2 items-center w-full py-1 px-2 rounded border-2 border-danger bg-white text-danger hover:bg-danger hover:text-white">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="font-semibold">
                        {{ __('Logout') }}
                    </span>
                </button>
            </form>
        </div>
    </div>

    <form action="">
        @csrf
        <!-- MOBILE SEARCH INPUT -->
        <div class="absolute top-full mt-4 right-0 w-[calc(100vw-2rem)] bg-white rounded-b-md drop-shadow-md p-2"
            x-cloak x-show="showSearchInput"
            x-transition:enter.duration.300ms
            x-transition:leave.duration.300ms
        >
            <input class="block border border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 w-full p-1.5 px-8 custom-placeholder" 
                type="text" name="search" id="search" placeholder="Cari">
            <span class="absolute inset-y-2 left-2 z-[1000] w-8 p-1.5 aspect-square flex items-center text-primary-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <button type="button" class="absolute inset-y-[9px] right-[9px] z-[1000] w-8 p-1.5 aspect-square flex items-center rounded-r bg-white text-gray-300"
                x-on:click=" showSearchInput = false ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>