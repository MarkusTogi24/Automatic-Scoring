@extends('layouts.master')

@section('content')

<div class="w-full"
    x-data="{
        showEditPopUp    : @if($errors->hasBag('edit_classroom')) true @else false @endif,
        showAddExamPopUp : @if($errors->hasBag('create_exam')) true @else false @endif,
    }">

    @include('components.session-alert')

    <div class="w-full mb-4 px-4 lg:px-10 {{ Auth::user()->role == "GURU" ? 'py-4 lg:py-10' : 'py-6 lg:py-20' }} rounded-lg bg-[url('/image/BANNER.png')] bg-cover bg-center text-gray-900">
        @if (Auth::user()->role == "GURU")
            <div class="w-full flex justify-end mb-4">
                <button x-on:click="showEditPopUp = true" type="button" class="text-sm lg:text-base py-1.5 px-5 bg-primary text-white rounded-md font-medium">
                    Edit Kelas
                </button>
            </div>
        @endif
        <p class="text-lg md:text-xl lg:text-2xl font-semibold mb-1 lg:mb-1.5">{{ $classroom->name }}</p>
        <p class="text-base lg:text-lg mb-1 lg:mb-1.5">Kode Kelas : {{ $classroom->enrollment_key }}</p>
        <p class="text-lg lg:text-xl">{{ $classroom->description }}</p>
    </div>

    @if (Auth::user()->role == "GURU")
        <!-- ACTION POP UP BUTTON GROUP FOR TEACHER -->
        <div class="relative w-fit max-w-full lg:w-full p-2 rounded-lg border border-primary-20 lg:border-none lg:p-0 overflow-x-auto custom-scrollbar mb-4 lg:mt-6">
            <div class="w-fit flex flex-nowrap gap-4">
                <button x-on:click="showAddExamPopUp = true" type="button" class="block whitespace-nowrap text-sm lg:text-base py-1.5 px-2 lg:px-5 bg-primary hover:bg-primary-70 text-white rounded-md font-medium">
                    Tambah Ujian Baru
                </button>
                <button x-on:click="showEditPopUp = true" type="button" class="block whitespace-nowrap text-sm lg:text-base py-1.5 px-2 lg:px-5 bg-primary hover:bg-primary-70 text-white rounded-md font-medium">
                    Unduh Rekap Nilai Keseluruhan
                </button>
            </div>
        </div>

        <!-- "EDIT CLASS" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showEditPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5" x-on:click.outside="showEditPopUp = false">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Edit Kelas
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
                        <div class="w-full max-h-80 overflow-y-auto mb-6 custom-scrollbar">
                            <!-- CLASSROOM NAME -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Nama Kelas') }}</p>
                                <input type="text" id="name" name="name" value="{{ $classroom->name }}"
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off">
                                @error('name', 'edit_classroom')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- CLASSROOM DESCRIPTION -->
                            <div class="">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Deskripsi Singkat') }}</p>
                                <textarea id="classroom_description" name="description" class="hidden" >
                                    {{ $classroom->description }}
                                </textarea>

                                <span id="Classroom_Description_Content" 
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ $classroom->description ? $classroom->description : 'Deskripsi Singkat' }}
                                </span>
                                @error('description', 'edit_classroom')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- SUBMIT BUTTON -->
                        <div class="flex justify-end">
                            <button type="submit" class="w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Edit Kelas') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- "ADD EXAM" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showAddExamPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5" x-on:click.outside="showAddExamPopUp = false">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Buat Ujian Baru
                        </p>
                        <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                            x-on:click="showAddExamPopUp = false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- POP UP BODY -->
                    <form action="{{ route('exam.store', $classroom->id) }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                            <!-- EXAM TITLE -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Judul Ujian') }}</p>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Judul Ujian"
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off">
                                    @error('name', 'create_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                            

                            <div class="mb-4 flex flex-col lg:flex-row gap-4 lg:gap-0 lg:justify-between">
                                <div class="w-full lg:w-[48%]">
                                    <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Waktu Mulai') }}</p>
                                    <input type="datetime-local" id="start_time" name="start_time"
                                        value="{{ old('start_time') }}"
                                        class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" >
                                    @error('start_time', 'create_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-[48%]">
                                    <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Durasi') }}</p>
                                    <input type="time" id="duration" name="duration" value="{{ old('duration') }}"
                                        class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" >
                                    @error('duration', 'create_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- EXAM DESCRIPTION -->
                            <div class="">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Deskripsi Ujian') }}</p>
                                <textarea id="exam_description" name="description" class="hidden" >
                                    {{ old('description') }}
                                </textarea>

                                <span id="Exam_Description_Content" 
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('description') ? old('description') : 'Deskripsi Ujian' }}
                                </span>
                                @error('description', 'create_exam')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- SUBMIT BUTTON -->
                        <div class="w-full">
                            <button type="submit" class="w-full text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Tambah') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    <div class="relative w-fit max-w-full lg:w-full p-4 rounded-lg border border-primary-20 lg:border-none lg:p-0 overflow-x-auto custom-scrollbar">
        <div class="w-[768px] lg:w-full flex flex-col gap-4">
            <div class="flex justify-between text-primary font-bold">
                @if (Auth::user()->role == "GURU")
                    <div class="w-[33%] py-1 text-center">Judul Ujian</div>
                    <div class="w-[32%] py-1 text-center">Status</div>
                    <div class="w-[33%] py-1 text-center">Waktu</div>
                @else
                    <div class="w-[30%] py-1 text-center">Judul Ujian</div>
                    <div class="w-[23%] py-1 text-center">Status</div>
                    <div class="w-[32%] py-1 text-center">Waktu</div>
                    <div class="w-[12%] py-1"></div>
                @endif
            </div>
            @forelse ($exams as $exam)
                <div class="flex justify-between text-white bg-primary-50 rounded-md">
                    @if (Auth::user()->role == "GURU")
                        <div class="w-[33%] text-sm px-4 py-[1.25rem]">
                            <a href="{{ route('exam.show', [$classroom->id, $exam->id]) }}" class="hover:font-semibold" >{{ $exam->name }} - {{ $classroom->name }}</a>
                        </div>
                        <p class="w-[32%] block text-sm px-4 py-[1.25rem] text-center">?? / ?? Siswa Menyelesaikan</p>
                        <p class="w-[33%] block text-sm px-4 py-[1.25rem] text-center">
                            {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                            {{ date_format(date_create($exam->start_time),"H:i") }} -
                            {{ date_format(date_create($exam->end_time),"H:i") }}
                        </p>
                    @else
                        <p class="w-[30%] block text-sm px-4 py-[1.25rem]">{{ $exam->name }} - {{ $classroom->name }}</p>
                        <p class="w-[23%] block text-sm px-4 py-[1.25rem] text-center">Belum Mengikuti</p>
                        <p class="w-[32%] block text-sm px-4 py-[1.25rem] text-center">
                            {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                            {{ date_format(date_create($exam->start_time),"H:i") }} -
                            {{ date_format(date_create($exam->end_time),"H:i") }}
                        </p>
                        <div class="w-[12%] text-sm px-4 py-[1.25rem] text-center">
                            <a href="{{ route('exam.show', [$classroom->id, $exam->id]) }}" class="underline underline-offset-1 hover:font-bold">
                                {{-- {{ $exam->is_open ? 'Mulai' : 'Lihat' }} --}}
                                Lihat
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <p class="block text-sm px-4 py-8 text-center text-white bg-primary-50 rounded-md">
                    Belum ada ujian di kelas ini.
                </p>
            @endforelse
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#Classroom_Description_Content").on("keyup", function () {
                let text = document.getElementById("Classroom_Description_Content").innerText;
                $("#classroom_description").val(text);
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
                let text = document.getElementById("Exam_Description_Content").innerText;
                $("#exam_description").val(text);
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