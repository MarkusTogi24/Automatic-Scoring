@extends('layouts.master')

@section('content')

<div class="w-full"
    x-data="{
        showEditPopUp    : @if($errors->hasBag('edit_classroom')) true @else false @endif,
        showAddExamPopUp : @if($errors->hasBag('edit_exam')) true @else false @endif,
        switchSection    : true,
        previewSection   : true,
        resultSection    : false,
    }">

    @include('components.session-alert')

    @if (Auth::user()->role == "GURU")
        <!-- BREADCRUMBS -->
        <div class="flex gap-1 mb-3 text-xs md:text-sm lg:text-base">
            <a href="{{ route('classroom.index') }}" class="text-primary hover:text-primary-50 font-semibold">
                Mata pelajaran
            </a>
            <p>/</p>
            <a href="{{ route('classroom.show', $classroom->id) }}" class="text-primary hover:text-primary-50 font-semibold">
                {{ $classroom->name }}
            </a>
            <p>/</p>
            <p>{{ $exam->name }}</p>
        </div>

        <!-- BANNER -->
        <div x-show="switchSection" class="w-full mb-4 p-4 lg:p-10 bg-primary-10 rounded-lg bg-[url('/image/BANNER.png')] bg-cover bg-center text-gray-900">
            <div class="w-full flex justify-end mb-4">
                <button x-on:click="showEditPopUp = true" type="button" 
                    class="text-sm lg:text-base py-1.5 px-5 bg-primary text-white hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center rounded-md font-medium">
                    Edit Ujian
                </button>
            </div>
            <p class="text-lg md:text-xl lg:text-2xl font-semibold mb-1 lg:mb-1.5">{{ $exam->name }} - {{ $classroom->name }}</p>
            <div class="flex flex-col lg:flex-row gap-1 lg:gap-2 items-start lg:items-center mb-1 lg:mb-1.5">
                <p class="block text-sm lg:text-base">
                    Waktu Mulai : 
                    <span class="font-semibold">
                        {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                        {{ date_format(date_create($exam->start_time),"H:i") }}
                    </span>
                </p>
                <p class="block text-sm lg:text-base">
                    Durasi Ujian : 
                    <span class="font-semibold">{{ $duration['human_format'] }}</span>
                </p>
                <p class="block text-sm lg:text-base">
                    Total Poin : 
                    <span class="font-semibold">100pt</span>
                </p>
            </div>
            <p class="text-lg lg:text-xl">{{ $exam->description }}</p>
        </div>

        <!-- "EDIT EXAM" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showEditPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5" x-on:click.outside="showEditPopUp = false">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Edit Ujian
                        </p>
                        <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                            x-on:click="showEditPopUp = false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- POP UP BODY -->
                    <form action="{{ route('classroom.update', $classroom->id) }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                            <!-- EXAM TITLE -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Judul Ujian') }}</p>
                                <input type="text" id="name" name="name" value="{{ $exam->name }}" placeholder="Judul Ujian"
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off">
                                    @error('name', 'edit_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                            

                            <div class="mb-4 flex flex-col lg:flex-row gap-4 lg:gap-0 lg:justify-between">
                                <div class="w-full lg:w-[48%]">
                                    <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Waktu Mulai') }}</p>
                                    <input type="datetime-local" id="start_time" name="start_time"
                                        value="{{ $exam->start_time }}"
                                        class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" >
                                    @error('start_time', 'edit_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-[48%]">
                                    <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Durasi') }}</p>
                                    <input type="time" id="duration" name="duration" value="{{ $duration['basic_format'] }}"
                                        class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" >
                                    @error('duration', 'edit_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- EXAM DESCRIPTION -->
                            <div class="">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Deskripsi Ujian') }}</p>
                                <textarea id="exam_description" name="description" class="hidden" >
                                    {{ $exam->description }}
                                </textarea>

                                <span id="Exam_Description_Content" 
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ $exam->description }}
                                </span>
                                @error('description', 'edit_exam')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- SUBMIT BUTTON -->
                        <div class="flex justify-end">
                            <button type="submit" class="w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Edit Ujian') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT MENU -->
        <div class="grid grid-cols-2 lg:flex gap-2 lg:gap-4 w-full mb-4 lg:mb-6">
            <button type="button" class="block py-1 lg:w-32 lg:text-center text-primary hover:text-primary-70 border-b-4"
                x-on:click="switchSection =! switchSection" x-bind:class="switchSection ? 'border-primary font-semibold' : 'border-white'">
                Pratinjau
            </button>
            <button type="button" class="block py-1 lg:w-32 lg:text-center text-primary hover:text-primary-70 border-b-4"
                x-on:click="switchSection =! switchSection" x-bind:class="switchSection ? 'border-white' : 'border-primary font-semibold'">
                Hasil
            </button>
        </div>
            
        <!-- MAIN CONTENT -> PREVIEW (QUESTIONS) -->
        <div class="w-full" x-show="switchSection">
            <!-- MAIN CONTENT -> QUESTION ACTION GROUP -->
            <div class="flex justify-end gap-2 lg:gap-4 w-full mb-4 lg:mb-6">
                <button type="button" 
                    class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                    Tambah Soal
                </button>
                <button type="button" 
                    class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                    Import Soal
                </button>
            </div>

            <!-- MAIN CONTENT -> QUESTIONS CONTAINER -->
            <div class="w-full flex flex-col gap-4">
                @forelse ($questions as $question)
                    <div class="p-4 rounded-md lg:rounded-lg border-2 border-primary overflow-hidden">
                        <div class="flex justify-end gap-2 mb-3">
                            <button type="button" 
                                class="block w-20 lg:w-24 py-1 text-sm lg:text-base rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-primary-30 text-center text-white">
                                Edit
                            </button>
                            <button type="button" 
                                class="block w-20 lg:w-24 py-1 text-sm lg:text-base rounded bg-danger hover:bg-danger-70 focus:ring-2 focus:outline-none focus:ring-danger-30 text-center text-white">
                                Hapus
                            </button>
                        </div>
                        <p class="text-base lg:text-lg text-gray-900 font-semibold block m-0 mb-2">
                            {{ $question->question }}
                        </p>
                        <p class="text-xs lg:text-sm text-gray-900 block m-0 mb-2">
                            Poin soal : {{ $question->max_score }}pt
                        </p>
                        <p class="text-sm lg:text-base text-gray-900 block m-0">
                            {{ $question->answer_key }}
                        </p>
                    </div>
                @empty
                    <p class="block text-sm px-4 py-8 text-center text-white bg-primary-50 rounded-md">
                        Belum ada soal ujian yang dibuat.
                    </p>
                @endforelse
            </div>
        </div>

        <!-- MAIN CONTENT -> EXAM RESULT -->
        <div class="w-full" x-cloak x-show="!switchSection">
            <!-- MAIN CONTENT -> EXAM RESULT ACTION GROUP -->
            <div class="flex justify-start w-full mb-4 lg:mb-8">
                <button type="button" 
                    class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                    Unduh Hasil Ujian
                </button>
            </div>

            <!-- MAIN CONTENT -> STUDENTS' EXAM RESULT -->
            <div class="relative w-fit max-w-full lg:w-full p-4 rounded-lg border border-primary-20 lg:border-none lg:p-0 overflow-x-auto custom-scrollbar">
                <div class="w-[768px] lg:w-full flex flex-col gap-4">
                    <div class="flex justify-between text-primary font-bold">
                        <div class="w-[33%] py-1 text-center">Nama Siswa</div>
                        <div class="w-[26%] py-1 text-center">Status</div>
                        <div class="w-[28%] py-1 text-center">Waktu</div>
                        <div class="w-[10%] py-1 text-center">Nilai</div>
                    </div>
                    {{-- @forelse ($exams as $exam) --}}
                        <div class="flex justify-between items-center text-white bg-primary-50 rounded-md">
                            <p class="w-[33%] block text-sm px-4 py-[1.25rem]">Marco Polonius Stefania Huruhara</p>
                            <p class="w-[26%] block text-sm px-4 py-[1.25rem] text-center">Belum Mengumpulkan</p>
                            <p class="w-[28%] block text-sm px-4 py-[1.25rem] text-center">
                                {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                                {{ date_format(date_create($exam->start_time),"H:i:s") }}
                            </p>
                            <p class="w-[10%] block text-sm px-4 py-[1.25rem] text-center">100</p>
                        </div>
                        <div class="flex justify-between items-center text-white bg-primary-50 rounded-md">
                            <p class="w-[33%] block text-sm px-4 py-[1.25rem]">Marco Polonius Stefania Huruhara</p>
                            <p class="w-[26%] block text-sm px-4 py-[1.25rem] text-center">Belum Mengumpulkan</p>
                            <p class="w-[28%] block text-sm px-4 py-[1.25rem] text-center">
                                {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                                {{ date_format(date_create($exam->start_time),"H:i:s") }}
                            </p>
                            <p class="w-[10%] block text-sm px-4 py-[1.25rem] text-center">100</p>
                        </div>
                        <div class="flex justify-between items-center text-white bg-primary-50 rounded-md">
                            <p class="w-[33%] block text-sm px-4 py-[1.25rem]">Marco Polonius Stefania Huruhara</p>
                            <p class="w-[26%] block text-sm px-4 py-[1.25rem] text-center">Belum Mengumpulkan</p>
                            <p class="w-[28%] block text-sm px-4 py-[1.25rem] text-center">
                                {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                                {{ date_format(date_create($exam->start_time),"H:i:s") }}
                            </p>
                            <p class="w-[10%] block text-sm px-4 py-[1.25rem] text-center">100</p>
                        </div>
                    {{-- @empty
                        <p class="block text-sm px-4 py-8 text-center text-white bg-primary-50 rounded-md">
                            Belum ada siswa di kelas ini.
                        </p>
                    @endforelse --}}
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#Classroom_Description_Content").on("keyup", function () {
                $("#classroom_description").val($(this).text());
            });

            $("#Classroom_Description_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Deskripsi Singkat"){
                    $(this).text(""); 
                }
            });

            $("#Classroom_Description_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Deskripsi Singkat"); 
                }
            });

            $("#Exam_Description_Content").on("keyup", function () {
                $("#exam_description").val($(this).text());
            });

            $("#Exam_Description_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Deskripsi Ujian"){
                    $(this).text(""); 
                }
            });

            $("#Exam_Description_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Deskripsi Ujian"); 
                }
            });
        });
    </script>
@endsection