@extends('layouts.master')

@section('content')

<div class="w-full"
    x-data="{
        showPopUp : @if(Auth::user()->role == "GURU" && $errors->hasBag('create_classroom')) true @else false @endif
    }">

    @include('components.session-alert')

    <div class="w-full lg:w-[calc(100vw-353px)] mb-4 relative">
        <!-- BUTTON TO SHOW "CREATE/JOIN CLASS" POP UP -->
        <button x-on:click="showPopUp = true" type="button" class="py-1.5 px-4 bg-primary text-white rounded-md font-medium">
            {{ Auth::user()->role == "GURU" ? 'Buat Kelas' : 'Gabung Kelas' }}
        </button>
    </div>

    <!-- "CREATE/JOIN CLASS" POP UP -->
    <div class="fixed z-[2220] inset-0"
        x-cloak x-show="showPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5" x-on:click.outside="showPopUp = false">
                <!-- POP UP HEADER -->
                <div class="flex justify-between items-center mb-6">
                    <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                        {{ Auth::user()->role == "GURU" ? 'Buat Kelas' : 'Gabung Kelas' }}
                    </p>
                    <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                        x-on:click="showPopUp = false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                @if (Auth::user()->role == "GURU")
                    <!-- POP UP BODY -->
                    <form action="{{ route('classroom.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="w-full max-h-80 overflow-y-auto mb-6 custom-scrollbar">
                            <!-- CLASSROOM NAME -->
                            <div class="mb-4">
                                <label for="name" class="block mb-2 text-base font-medium text-gray-900">{{ __('Nama Kelas') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off" placeholder="Nama Kelas">
                                @error('name', 'create_classroom')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- CLASSROOM DESCRIPTION -->
                            <div class="">
                                <p class="block mb-2 text-base font-medium text-gray-900">{{ __('Deskripsi Singkat') }}</p>
                                <textarea id="description" name="description" class="hidden" >
                                    {{ old('description') }}
                                </textarea>

                                <span id="Description_Content" 
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-1 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('description') ? old('description') : 'Deskripsi Singkat' }}
                                </span>

                                @error('description', 'create_classroom')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- SUBMIT BUTTON -->
                        <div class="flex justify-end">
                            <button type="submit" class="w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Buat Kelas') }}
                            </button>
                        </div>
                    </form>
                @else
                    <form action="{{ route('classroom_member.enroll') }}" method="POST">
                        @method('POST')
                        @csrf
                        <!-- CLASSROOM NAME -->
                        <div class="mb-6">
                            <label for="enrollment_key" class="block mb-2 text-base font-medium text-gray-900">{{ __('Masukkan Kode Kelas') }}</label>
                            <input type="text" id="enrollment_key" name="enrollment_key" value="{{ old('enrollment_key') }}"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder"
                                required autocomplete="off" placeholder="Kode Kelas">
                        </div>
                        <!-- SUBMIT BUTTON -->
                        <div class="flex justify-end">
                            <button type="submit" class="w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Gabung Kelas') }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if ($classrooms)
        <div class="w-full lg:w-[calc(100vw-361px)] grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @foreach ($classrooms as $classroom)
                <div class="relative w-full p-2 bg-white rounded-md drop-shadow-md overflow-hidden">
                    <div class="w-full aspect-video bg-[url('/image/BG2.png')] bg-cover bg-center rounded-md mb-2">
                    </div>
                    <div class="w-full h-16 relative px-2">
                        <a href="{{ route('classroom.show', $classroom->class_id) }}" class="flex flex-col text-primary hover:text-primary-70 font-medium tracking-wide w-fit">
                            <span class="block m-0">{{ $classroom->name }}</span>
                            @php
                                $exploded = explode(" ",$classroom->description);
                            @endphp
                            <span class="block m-0">{{ implode(" ", array_splice($exploded, 0, 2)) }}</span>
                        </a>
                        <div class="absolute rounded-full w-[72px] h-[72px] bottom-10 right-5 overflow-hidden">
                            <img src="{{ $classroom->teacher_pic ? asset('storage/'.$classroom->teacher_pic) : asset('image/PP.jpg') }}" 
                                class="w-full h-auto" alt="TEACHER">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else  
        <div class="w-full h-[calc(100vh-9.5rem)] flex flex-col items-center justify-center">
            <img src="{{ asset('image/EMPTY.jpg') }}" class="w-auto h-1/2 mb-3" alt="EMPTY">
            <p class="text-primary text-lg font-semibold tracking-wide">
                {{ Auth::user()->role == "GURU" ? 'Buat' : 'Gabung' }} kelas untuk memulai
            </p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#Description_Content").on("keyup", function () {
                $("#description").val($(this).text());
            });
            
            $("#Description_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Deskripsi Singkat"){
                    $(this).text(""); 
                }
            });

            $("#Description_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Deskripsi Singkat"); 
                }
            });
        });
    </script>
@endsection