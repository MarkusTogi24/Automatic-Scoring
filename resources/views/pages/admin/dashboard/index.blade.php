@extends('layouts.master')

@section('content')

<div class="w-full">
    @include('components.session-alert')

    <div class="w-full lg:w-[calc(100vw-361px)] grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        
        <div class="relative w-full p-4 lg:p-6 bg-white rounded-md drop-shadow-md overflow-hidden text-primary">
            <div class="relative w-full mb-2 lg:mb-3">
                <p class="block m-0 text-lg lg:text-xl font-medium">Jumlah Siswa Terdaftar</p>
                <div class="absolute w-1/3 lg:w-1/2 h-[2px] top-full bg-primary"></div>
            </div>
            <div class="w-full flex items-center justify-between">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 w-28 lg:h-24 lg:w-24" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="block m-0 text-7xl lg:text-6xl font-black">{{ $students_count }}</p>
                    <p class="block m-0 text-right">akun</p>
                </div>
            </div>
        </div>

        <div class="relative w-full p-4 lg:p-6 bg-white rounded-md drop-shadow-md overflow-hidden text-primary">
            <div class="relative w-full mb-2 lg:mb-3">
                <p class="block m-0 text-lg lg:text-xl font-medium">Jumlah Guru Terdaftar</p>
                <div class="absolute w-1/3 lg:w-1/2 h-[2px] top-full bg-primary"></div>
            </div>
            <div class="w-full flex items-center justify-between">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 w-28 lg:h-24 lg:w-24" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="block m-0 text-7xl lg:text-6xl font-black">{{ $teachers_count }}</p>
                    <p class="block m-0 text-right">akun</p>
                </div>
            </div>
        </div>

        <div class="relative w-full p-4 lg:p-6 bg-white rounded-md drop-shadow-md overflow-hidden text-primary">
            <div class="relative w-full mb-2 lg:mb-3">
                <p class="block m-0 text-lg lg:text-xl font-medium">Jumlah Kelas Terdaftar</p>
                <div class="absolute w-1/3 lg:w-1/2 h-[2px] top-full bg-primary"></div>
            </div>
            <div class="w-full flex items-center justify-between">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-28 w-28 lg:h-24 lg:w-24" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                </div>
                <div>
                    <p class="block m-0 text-7xl lg:text-6xl font-black">{{ $classrooms_count }}</p>
                    <p class="block m-0 text-right">kelas</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script>
    </script>
@endsection