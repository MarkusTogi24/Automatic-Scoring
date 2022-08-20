@extends('layouts.master')

@section('content')

<div class="w-full"
    x-data="{
        showEditPopUp           : @if($errors->hasBag('edit_exam')) true @else false @endif,
        showAddQuestionPopUp    : @if($errors->hasBag('create_question')) true @else false @endif,
        showUploadQuestionPopUp : @if($errors->hasBag('upload_question')) true @else false @endif,
        showEditQuestionPopUp   : @if($errors->hasBag('edit_question')) true @else false @endif,
        showDeleteQuestionPopUp : false,
        switchSection           : true,
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
                    <span class="font-semibold">{{ $total_score }} pt</span>
                </p>
            </div>
            <p class="text-lg lg:text-xl">{{ $exam->description }}</p>
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
                <button type="button" x-on:click="showAddQuestionPopUp = true"
                    class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                    Tambah Soal
                </button>
                <button type="button" x-on:click="showUploadQuestionPopUp = true"
                    class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                    Import Soal
                </button>
            </div>

            <!-- MAIN CONTENT -> QUESTIONS CONTAINER -->
            <div class="w-full flex flex-col gap-4">
                @forelse ($questions as $question)
                    <div class="p-4 rounded-md lg:rounded-lg border-2 border-primary overflow-hidden">
                        <div class="flex justify-end gap-2 mb-3">
                            <button type="button" id="editQuestionBtn_{{ $question->id }}" x-on:click="showEditQuestionPopUp = true"
                                class="edit-question-btn block w-20 lg:w-24 py-1 text-sm lg:text-base rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-primary-30 text-center text-white">
                                Edit
                            </button>
                            <button type="button" id="deleteQuestionBtn_{{ $question->id }}" x-on:click="showDeleteQuestionPopUp = true"
                                class="delete-question-btn block w-20 lg:w-24 py-1 text-sm lg:text-base rounded bg-danger hover:bg-danger-70 focus:ring-2 focus:outline-none focus:ring-danger-30 text-center text-white">
                                Hapus
                            </button>
                        </div>
                        {{-- <p class="text-base lg:text-lg text-primary font-bold block m-0 mb-1">Pertanyaan</p> --}}
                        <p id="questionText_{{ $question->id }}" class="text-base lg:text-lg text-gray-900 font-semibold block m-0 mb-3 {{-- whitespace-pre-line --}}">
                            {{ $question->question }}
                        </p>
                        <p class="text-xs lg:text-sm text-gray-900 block m-0 mb-3" id="questionScore_{{ $question->id }}">
                            {{-- <span class="text-primary font-semibold">Poin soal</span> : {{ $question->max_score }}pt --}}
                            Poin soal : {{ $question->max_score }} pt
                        </p>
                        {{-- <p class="text-base lg:text-lg text-primary font-bold block m-0 mb-1">Jawaban</p> --}}
                        <p class="text-sm lg:text-base text-gray-900 block m-0  {{-- whitespace-pre-line --}}" id="questionAnswer_{{ $question->id }}">
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
                <a href="{{ route('exam.export-result', $exam->id) }}" type="button" 
                    class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                    Unduh Hasil Ujian
                </a>
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
                    @forelse ($classroom_members as $member)
                        <div class="flex justify-between items-center text-white bg-primary-50 rounded-md">
                            <p class="w-[33%] block text-sm px-4 py-[1.25rem]">{{ $member->name }}</p>
                            <p class="w-[26%] block text-sm px-4 py-[1.25rem] text-center">
                                @if ($member->total_score === null)
                                    {{ $exam->is_open ? 'Belum' : 'Tidak' }} Mengumpulkan
                                @else
                                    Sudah Mengumpulkan
                                @endif
                            </p>
                            <p class="w-[28%] block text-sm px-4 py-[1.25rem] text-center">
                                @if ($member->total_score === null)
                                    -
                                @else
                                    {{ Carbon\Carbon::parse($member->submit_time)->isoFormat('D MMMM Y') }}, 
                                    {{ date_format(date_create($member->submit_time),"H:i:s") }}
                                @endif
                            </p>
                            <p class="w-[10%] block text-sm px-4 py-[1.25rem] text-center">
                                {{ $member->total_score === null ? '-' : $member->total_score }}
                            </p>
                        </div>
                    @empty
                        <p class="block text-sm px-4 py-8 text-center text-white bg-primary-50 rounded-md">
                            Belum ada siswa di kelas ini.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- "EDIT EXAM" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showEditPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5">
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
                    <form action="{{ route('exam.update', [$classroom, $exam]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                            <!-- EXAM TITLE -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Judul Ujian') }}</p>
                                <input type="text" id="name" name="name" placeholder="Judul Ujian"
                                    value="{{ old('name') ? old('name') : $exam->name }}" 
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off">
                                    @error('name', 'edit_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                            

                            <div class="mb-4 flex flex-col lg:flex-row gap-4 lg:gap-0 lg:justify-between">
                                <!-- START TIME -->
                                <div class="w-full lg:w-[48%]">
                                    <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Waktu Mulai') }}</p>
                                    <input type="datetime-local" id="start_time" name="start_time"
                                        value="{{ old('start_time') ? old('start_time') : date_format(date_create($exam->start_time),'Y-m-d\TH:i') }}"
                                        class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" >
                                    @error('start_time', 'edit_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- DURATION -->
                                <div class="w-full lg:w-[48%]">
                                    <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Durasi') }}</p>
                                    <input type="time" id="duration" name="duration" 
                                        value="{{ old('duration') ? old('duration') : $duration['basic_format'] }}"
                                        class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" >
                                    @error('duration', 'edit_exam')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- EXAM DESCRIPTION -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Deskripsi Ujian') }}</p>
                                <textarea id="exam_description" name="description" class="hidden" >
                                    {{ old('description') ? old('description') : $exam->description }}
                                </textarea>

                                <span id="Exam_Description_Content" 
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('description') ? old('description') : $exam->description }}
                                </span>
                                @error('description', 'edit_exam')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- OPEN STATUS -->
                            <div class="" x-data="{ switchChecked : @if($exam->is_open == 1) true @else false @endif}">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Status Ujian') }}</p>
                                <div class="flex justify-between">
                                    <div class="w-[48%] flex items-center pl-2 rounded border-2"
                                        x-bind:class=" switchChecked ? 'border-primary-20 bg-primary-10' : 'border-gray-200' ">
                                        <input type="radio" value="1" name="is_open" id="is_open_true" @checked($exam->is_open == 1)
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary-50 focus:ring-2" x-on:click="switchChecked =! switchChecked">
                                        <label for="is_open_true" class="py-2 ml-1.5 w-full text-sm font-medium " 
                                            {{-- x-on:click="switchChecked =! switchChecked" --}}
                                            x-bind:class=" switchChecked ? 'text-primary-50 font-medium' : 'text-gray-500' ">
                                            Terbuka
                                        </label>
                                    </div>
                                    <div class="w-[48%] flex items-center pl-2 rounded border-2"
                                        x-bind:class=" switchChecked ? 'border-gray-200' : 'border-primary-20 bg-primary-10' ">
                                        <input type="radio" value="0" name="is_open" id="is_open_false" @checked($exam->is_open == 0)
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary-50 focus:ring-2" x-on:click="switchChecked =! switchChecked">
                                        <label for="is_open_false" class="py-2 ml-1.5 w-full text-sm font-medium" 
                                            {{-- x-on:click="switchChecked =! switchChecked" --}}
                                            x-bind:class=" switchChecked ? 'text-gray-500' : 'text-primary-50 font-medium' ">
                                            Tertutup
                                        </label>
                                    </div>
                                </div>
                                @error('is_open', 'edit_exam')
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

        <!-- "ADD QUESTION" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showAddQuestionPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Tambah Soal
                        </p>
                        <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                            x-on:click="showAddQuestionPopUp = false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- POP UP BODY -->
                    <form action="{{ route('question.store', [$classroom->id, $exam->id]) }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                            {{-- <small>class_id : {{ $classroom->id }}</small>
                            <small>exam_id : {{ $exam->id }}</small> --}}
                            <!-- QUESTION TEXT -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Pertanyaan') }}</p>
                                <textarea id="question_text" name="question" class="hidden" >
                                    {{ old('question') }}
                                </textarea>

                                <span id="Question_Text_Content"
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('question') ? old('question') : 'Teks pertanyaan' }}
                                </span>
                                @error('question', 'create_question')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- QUESTION ANSWER -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Jawaban') }}</p>
                                <textarea id="question_answer" name="answer_key" class="hidden" >
                                    {{ old('answer_key') }}
                                </textarea>

                                <span id="Question_Answer_Content"
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('answer_key') ? old('answer_key') : 'Teks jawaban' }}
                                </span>
                                @error('answer_key', 'create_question')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- QUESTION SCORE -->
                            <div class="">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Bobot soal') }}</p>
                                <input type="number" min="0" id="max_score" name="max_score" value="{{ old('max_score') }}" placeholder="0"
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off">
                                    @error('max_score', 'create_question')
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

        <!-- "UPLOAD QUESTION" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showUploadQuestionPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Impor Soal
                        </p>
                        <button type="button" id="closeImportQuestionModal" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                            x-on:click="showUploadQuestionPopUp = false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    
                    <form action="{{ route('question.upload', [$classroom, $exam]) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('POST')
                        <!-- POP UP BODY -->
                        <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar" 
                            x-data="{switchDisplay : true}">
                            <button type="button" x-on:click="switchDisplay =! switchDisplay"
                                class="flex items-center w-full bg-white text-primary font-semibold text-base py-2.5 justify-between"
                                x-bind:class="switchDisplay && 'border-b-2 border-primary mb-4' ">
                                <span>
                                    <span x-show="switchDisplay">Lihat</span>
                                    <span x-show="!switchDisplay">Tutup</span>
                                    Panduan Impor Soal
                                </span>
                                <span x-bind:class="switchDisplay || 'rotate-90' ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                            <!-- IMPORT QUESTION GUIDANCE -->
                            <div class="mb-4 border border-primary rounded-lg p-4" x-show="!switchDisplay">
                                <p class="block mb-2 text-base font-bold text-gray-900">
                                    Bagaimana cara untuk mengimpor soal ujian?
                                </p>
                                <p class="block mb-2 text-sm text-gray-900">
                                    <span class="font-semibold">Penting!</span> Pastikan anda mengunggah fail yang sesuai dengan templat yang telah disediakan. Silakan unduh dan baca terlebih dahulu fail <span class="font-semibold text-primary">Panduan Impor</span>, kemudian gunakan juga fail <span class="font-semibold text-success">Templat Excel</span> di bawah ini untuk memudahkan anda ketika ingin mengimpor soal, dan menghindari kemungkinan terjadinya kesalahan yang dapat memicu kerusakan pada sistem.
                                </p>
                                <div class="flex gap-4 justify-start items-center">
                                    <div class="">
                                        <p class="block mb-2 text-base font-bold text-gray-900">
                                            Panduan Impor
                                        </p>
                                        <a href="{{ asset('file/Panduan Impor Soal.pdf') }}" class="flex items-center gap-3 w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm pl-3 pr-5 py-1.5" download>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </span>
                                            <span>Unduh</span>
                                        </a>
                                    </div>
                                    <div class="">
                                        <p class="block mb-2 text-base font-bold text-gray-900">
                                            Templat Excel
                                        </p>
                                        <a href="{{ asset('file/Templat Impor Soal.xlsx') }}" class="flex items-center gap-3 w-fit text-white bg-success hover:bg-success-70 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-md text-sm pl-3 pr-5 py-1.5" download>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </span>
                                            <span>Unduh</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- IMPORT QUESTION DRAG AND DROP -->
                            <div class="w-full mb-4 bg-primary-10" x-show="switchDisplay">
                                <label id="importQuestionInputLabel" class="block cursor-pointer w-full text-center" for="questionFile">
                                    <div id="fileDropZone" class="relative py-8 border-2 transition-all ease-in-out duration-200 border-white text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="m-auto h-20 w-20" viewBox="0 0 20 20"
                                            fill="#4080F4">
                                            <path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z" />
                                            <path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
                                        </svg>
                                        <p class="text-xl mb-2 font-thin">Tarik file ke sini</p>
                                        <p class="mb-2">- atau -</p>
                                        <div id="insideFileLabel" class="w-fit m-auto cursor-pointer text-sm py-1 px-2 rounded bg-primary-20 hover:bg-primary-30">
                                            Pilih file dari komputer anda
                                        </div>
                                        <div id="dropMsgContainer"
                                            class="hidden absolute z-[3000] left-[16.667%] top-[40%] w-2/3 rounded-md bg-red-400 h-1/5 text-center flex-col justify-center">
                                            {{-- <p id="dropMsg" class="text-lg text-white font-thin">Pilih 1 File dalam 1 waktu</p> --}}
                                            <p id="dropMsg" class="text-base text-white font-thin"></p>
                                        </div>
                                    </div>
                                </label>
                                <input name="questionFile" id="questionFile" class="hidden" type="file">
                            </div>
                            <div id="filePreview" class="hidden grid-cols-5 gap-3 w-full bg-blue-50 p-4 mb-4" x-show="switchDisplay">
                                <p class="block col-span-5 text-primary font-semibold">File yang anda pilih :</p>
                                <div class="col-span-5 grid grid-cols-12 bg-white rounded px-2 py-3">
                                    <div class="flex flex-col justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 m-auto" viewBox="0 0 30 30" fill="#26e07f">
                                            <path d="M 15 3 A 2 2 0 0 0 14.599609 3.0429688 L 14.597656 3.0410156 L 4.6289062 5.0351562 L 4.6269531 5.0371094 A 2 2 0 0 0 3 7 L 3 23 A 2 2 0 0 0 4.6289062 24.964844 L 14.597656 26.958984 A 2 2 0 0 0 15 27 A 2 2 0 0 0 17 25 L 17 5 A 2 2 0 0 0 15 3 z M 19 5 L 19 8 L 21 8 L 21 10 L 19 10 L 19 12 L 21 12 L 21 14 L 19 14 L 19 16 L 21 16 L 21 18 L 19 18 L 19 20 L 21 20 L 21 22 L 19 22 L 19 25 L 25 25 C 26.105 25 27 24.105 27 23 L 27 7 C 27 5.895 26.105 5 25 5 L 19 5 z M 23 8 L 24 8 C 24.552 8 25 8.448 25 9 C 25 9.552 24.552 10 24 10 L 23 10 L 23 8 z M 6.1855469 10 L 8.5878906 10 L 9.8320312 12.990234 C 9.9330313 13.234234 10.013797 13.516891 10.091797 13.837891 L 10.125 13.837891 C 10.17 13.644891 10.258531 13.351797 10.394531 12.966797 L 11.785156 10 L 13.972656 10 L 11.359375 14.955078 L 14.050781 19.998047 L 11.716797 19.998047 L 10.212891 16.740234 C 10.155891 16.625234 10.089203 16.393266 10.033203 16.072266 L 10.011719 16.072266 C 9.9777187 16.226266 9.9105937 16.458578 9.8085938 16.767578 L 8.2949219 20 L 5.9492188 20 L 8.7324219 14.994141 L 6.1855469 10 z M 23 12 L 24 12 C 24.552 12 25 12.448 25 13 C 25 13.552 24.552 14 24 14 L 23 14 L 23 12 z M 23 16 L 24 16 C 24.552 16 25 16.448 25 17 C 25 17.552 24.552 18 24 18 L 23 18 L 23 16 z M 23 20 L 24 20 C 24.552 20 25 20.448 25 21 C 25 21.552 24.552 22 24 22 L 23 22 L 23 20 z"></path>
                                        </svg>
                                    </div>
                                    <p id="selectedFileName"
                                        class="flex flex-col justify-center col-span-11 italic break-words">
                                    </p>
                                </div>
                                <div id="changeFileBtn"
                                    class="col-start-5 rounded text-sm font-medium text-white cursor-pointer bg-blue-400 hover:bg-blue-300 text-center">
                                    Ganti File</div>
                            </div>
                            
                            <!-- SUBMIT BUTTON -->
                            <div class="w-full">
                                <button type="submit" id="submitFileBtn" disabled
                                    class="w-full text-white font-medium rounded-md text-sm px-5 py-2.5 text-center disabledBtn">
                                    {{ __('Tambah') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- "EDIT QUESTION" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showEditQuestionPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Edit Soal
                        </p>
                        <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                            x-on:click="showEditQuestionPopUp = false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- POP UP BODY -->
                    <form action="{{ route('question.update', [$classroom->id, $exam->id]) }}" method="POST">
                        @method('PUT') @csrf
                        <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                            <!-- QUESTION ID -->
                            <input type="hidden" name="question_id" id="edit_question_id" value="{{ old('question_id') }}">
                            
                            <!-- QUESTION TEXT -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Pertanyaan') }}</p>
                                <textarea id="edit_question_text" name="question" class="hidden" >
                                    {{ old('question') }}
                                </textarea>

                                <span id="Edit_Question_Text_Content"
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('question') ? old('question') : 'Teks pertanyaan' }}
                                </span>
                                @error('question', 'edit_question')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- QUESTION ANSWER -->
                            <div class="mb-4">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Jawaban') }}</p>
                                <textarea id="edit_question_answer" name="answer_key" class="hidden" >
                                    {{ old('answer_key') }}
                                </textarea>

                                <span id="Edit_Question_Answer_Content"
                                    class="overflow-hidden border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 focus:ring-125 outline-none"
                                    role="textbox" contenteditable>
                                    {{ old('answer_key') ? old('answer_key') : 'Teks jawaban' }}
                                </span>
                                @error('answer_key', 'edit_question')
                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- QUESTION SCORE -->
                            <div class="">
                                <p class="block mb-2 text-sm font-medium text-gray-900">{{ __('Bobot soal') }}</p>
                                <input type="number" min="0" id="edit_max_score" name="max_score" value="{{ old('max_score') }}" placeholder="0"
                                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                    autocomplete="off">
                                    @error('max_score', 'edit_question')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                        <!-- SUBMIT BUTTON -->
                        <div class="w-full">
                            <button type="submit" class="w-full text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Edit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- "DELETE QUESTION" POP UP FOR TEACHER -->
        <div class="fixed z-[2220] inset-0"
            x-cloak x-show="showDeleteQuestionPopUp">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                    <!-- POP UP HEADER -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                            Hapus Soal
                        </p>
                        <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                            x-on:click="showDeleteQuestionPopUp = false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- POP UP BODY -->
                    <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                        <!-- QUESTION TEXT -->
                        <div class="">
                            <p class="block mb-2 text-base font-bold text-gray-700">Anda akan menghapus soal berikut : </p>
                            <p class="block mb-2 text-sm font-semibold text-gray-700">Pertanyaan :</p>
                            <p class="block mb-2 text-sm font-medium text-gray-700" id="delete_question_text"></p>
                            <p class="block mb-2 text-sm font-semibold text-gray-700">Bobot nilai :</p>
                            <p class="block mb-2 text-sm font-medium text-gray-700" id="delete_question_score"></p>
                            <p class="block mb-2 text-sm font-semibold text-gray-700">Jawaban :</p>
                            <p class="block mb-2 text-sm font-medium text-gray-700" id="delete_question_answer"></p>
                            <p class="block text-base font-bold text-gray-700">Anda yakin untuk melanjutkan aksi ini?</p>
                        </div>
                    </div>
                    <!-- SUBMIT BUTTON -->
                    <form action="{{ route('question.delete', [$classroom->id, $exam->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="w-full">
                            <button type="submit" name="question_id" id="delete_question_id" class="w-full text-white bg-danger hover:bg-danger-70 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                                {{ __('Hapus') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="w-full mb-4 p-4 lg:p-10 border border-primary rounded-lg text-gray-900">
            <div class="flex flex-col lg:flex-row gap-3 lg:gap-0 lg:justify-between items-start lg:items-center mb-4">
                <p class="block m-0 text-lg font-bold tracking-wide">
                    {{ $exam->name }}
                </p>
                <p class="block m-0 text-xs lg:text-sm font-medium">
                    Tenggat :
                    {{ Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') }}, 
                    {{ date_format(date_create($exam->start_time),"H:i") }}
                </p>
            </div>
            <p class="block m-0 mb-4 text-sm xl:text-base">{{ $exam->description }}</p>
            @if ($student_exam > 0)
                <p class="flex items-center gap-3 mb-8 text-primary">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="font-semibold">Sudah diselesaikan</span>
                </p>
            @else
                <p class="flex items-center gap-3 mb-8">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>{{ $exam->is_open ? 'Belum' : 'Tidak' }} diselesaikan</span>
                </p>
            @endif

            <div class="flex justify-end">
                @if ($exam->is_open)
                    @if ($student_exam > 0)
                        <a href="{{ route('exam.result', [$classroom->id, $exam->id]) }}" 
                            class="text-sm lg:text-base py-1.5 px-5 text-white bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center rounded-md font-medium">
                            Detail Ujian
                        </a>
                    @else
                        <a href="{{ route('exam.start', [$classroom->id, $exam->id]) }}" 
                            class="text-sm lg:text-base py-1.5 px-5 text-white bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center rounded-md font-medium">
                            {{ $student_answer_count > 0 ? 'Lanjutkan Ujian' : 'Mulai Ujian' }}
                        </a>
                    @endif
                @else
                    <button type="button" disabled class="text-sm lg:text-base text-white py-1.5 px-5 disabledBtn text-center rounded-md font-medium">
                        Ujian Sudah Ditutup
                    </button>
                @endif
            </div>
        </div>
        <a href="{{ route('classroom.show', $classroom->id) }}" class="flex items-center gap-2 w-fit lg:gap-3 text-sm lg:text-base py-1.5 bg-white text-primary hover:text-primary-70 font-semibold">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
            <span>Kembali</span>
        </a>
    @endif

</div>
@endsection

@section('scripts')
    <script>
        function validateExtension(file) {
            let inputLabel           = document.getElementById("importQuestionInputLabel");
            let preview              = document.getElementById("filePreview");
            let nameField            = document.getElementById("selectedFileName");
            let submitFileBtn        = document.getElementById("submitFileBtn");
            let fileDropZone         = document.getElementById("fileDropZone");
            let dropMessage          = document.getElementById("dropMsg");
            let dropMessageContainer = document.getElementById("dropMsgContainer");

            if (file) {
                let allowedExt  = ['xls', 'xlsx', 'xlsm'];
                let fileExt     = file.name.split(".")[1];
                if (allowedExt.includes(fileExt)) {
                    inputLabel.classList.remove("block");
                    inputLabel.classList.add("hidden");
                    preview.classList.remove("hidden");
                    preview.classList.add("grid");
                    nameField.innerText = file.name;
                    submitFileBtn.removeAttribute("disabled");
                    submitFileBtn.classList.remove("disabledBtn");
                    submitFileBtn.classList.add("enabledBtn");
                } else {
                    fileDropZone.classList.contains("dragOver") ? 
                        fileDropZone.classList.remove("dragOver") : fileDropZone.classList.remove("border-white");
                    fileDropZone.classList.add("border-red-500");
                    dropMessageContainer.classList.remove("hidden");
                    dropMessageContainer.classList.add("flex");
                    dropMessage.innerHTML =
                        `Pilih file <span class="italic">.xlsx</span>, <span class="italic">.xlsm</span> atau <span class="italic">.xls</span>`;
                }
            }
        }
        $(document).ready(function () {
            // handle 'dragover' event
            $("#fileDropZone").on("dragover", function (e) {
                e.preventDefault();
                e.stopPropagation();

                if ($("#dropMsgContainer").hasClass('flex')) {
                    $("#dropMsgContainer").removeClass('flex');
                    $("#dropMsgContainer").addClass('hidden')
                }
                $(this).hasClass('border-red-500') ? $(this).removeClass('border-red-500') : $(this).removeClass('border-white');

                $(this).addClass('dragOver');
            });

            // handle 'dragleave' event
            $("#fileDropZone").on("dragleave", function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragOver');
                $(this).addClass('border-white');
            });

            // handle 'drop' event
            $("#fileDropZone").on("drop", function (e) {
                e.preventDefault();
                e.stopPropagation();

                var droppedFile = e.originalEvent.dataTransfer.files;

                if (droppedFile.length > 1) {
                    $("#dropMsgContainer").removeClass('hidden');
                    $("#fileDropZone").removeClass('dragOver');
                    $("#fileDropZone").addClass('border-red-500');
                    $("#dropMsgContainer").addClass('flex');
                    $("#dropMsg").text('Pilih satu file dalam satu waktu');
                } else {
                    let fileTarget = document.getElementById("questionFile");
                    fileTarget.files = droppedFile;
                    validateExtension(fileTarget.files[0]);
                }
            });

            // Validate the file (only validate the extension)
            $("#questionFile").change(function (e) {
                let questionFile = document.getElementById("questionFile");
                validateExtension(questionFile.files[0]);
            });

            // Hide Message Container (if there/if appear) when user click the label
            $("#importQuestionInputLabel").click(function () {
                if ($("#fileDropZone").hasClass('border-red-500')) {
                    $("#fileDropZone").removeClass('border-red-500');
                }
                if ($("#dropMsgContainer").hasClass('flex')) {
                    $("#dropMsgContainer").removeClass('flex');
                    $("#dropMsgContainer").addClass('hidden');
                }
            });

            // Execute if user want to change the file
            $("#changeFileBtn").click(function () {
                let inputFile = document.getElementById("questionFile");
                inputFile.value = "";

                $("#filePreview").removeClass('grid');
                $("#filePreview").addClass('hidden');

                $("#importQuestionInputLabel").removeClass('hidden');
                $("#importQuestionInputLabel").addClass('block');

                if ($("#fileDropZone").hasClass('dragOver')) {
                    $("#fileDropZone").removeClass('dragOver');
                    $("#fileDropZone").addClass('border-white');
                }

                $("#submitFileBtn").attr("disabled", true);
                $("#submitFileBtn").removeClass('enabledBtn');
                $("#submitFileBtn").addClass('disabledBtn');
            });

            // Executed when user click pop-up's close button
            $("#closeImportQuestionModal").click(function () {
                // Hide File Preview
                if ($("#filePreview").hasClass('grid')) {
                    $("#filePreview").removeClass('grid');
                    $("#filePreview").addClass('hidden');
                }

                // Show the label
                if ($("#importQuestionInputLabel").hasClass('hidden')) {
                    $("#importQuestionInputLabel").removeClass('hidden');
                    $("#importQuestionInputLabel").addClass('block');
                }

                // Hide Message Container
                if ($("#dropMsgContainer").hasClass('flex')) {
                    $("#dropMsgContainer").removeClass('flex');
                    $("#dropMsgContainer").addClass('hidden');
                }

                // Hide blue border
                if ($("#fileDropZone").hasClass('dragOver')) {
                    $("#fileDropZone").removeClass('dragOver');
                    $("#fileDropZone").addClass('border-white');
                }

                // Hide red border
                if ($("#fileDropZone").hasClass('border-red-500')) {
                    $("#fileDropZone").removeClass('border-red-500');
                    $("#fileDropZone").addClass('border-white');
                }

                // Disable submit button 
                if ($("#submitFileBtn").hasClass('enabledBtn')) {
                    $("#submitFileBtn").attr("disabled", true);
                    $("#submitFileBtn").removeClass('enabledBtn');
                    $("#submitFileBtn").addClass('disabledBtn');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
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

            $("#Question_Text_Content").on("keyup", function () {
                let text = document.getElementById("Question_Text_Content").innerText;
                $("#question_text").val(text);
            });

            $("#Question_Text_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Teks pertanyaan"){
                    $(this).text(""); 
                }
            });

            $("#Question_Text_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Teks pertanyaan"); 
                }
            });

            $("#Question_Answer_Content").on("keyup", function () {
                let text = document.getElementById("Question_Answer_Content").innerText;
                $("#question_answer").val(text);
            });

            $("#Question_Answer_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Teks jawaban"){
                    $(this).text(""); 
                }
            });

            $("#Question_Answer_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Teks jawaban"); 
                }
            });

            $("#Edit_Question_Text_Content").on("keyup", function () {
                let text = document.getElementById("Edit_Question_Text_Content").innerText;
                $("#edit_question_text").val(text);
            });

            $("#Edit_Question_Text_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Teks pertanyaan"){
                    $(this).text(""); 
                }
            });

            $("#Edit_Question_Text_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Teks pertanyaan"); 
                }
            });

            $("#Edit_Question_Answer_Content").on("keyup", function () {
                let text = document.getElementById("Edit_Question_Answer_Content").innerText;
                $("#edit_question_answer").val(text);
            });

            $("#Edit_Question_Answer_Content").on('focusin', function () { 
                let text = $(this).text().trim();
                if(text == "Teks jawaban"){
                    $(this).text(""); 
                }
            });

            $("#Edit_Question_Answer_Content").on('focusout', function () { 
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Teks jawaban"); 
                }
            });

            $(".edit-question-btn").click(function (e) { 
                e.preventDefault();
                let id = $(this).attr("id").split("_")[1];
                let questionText    = document.getElementById("questionText_"+id).innerText;
                let questionAnswer  = document.getElementById("questionAnswer_"+id).innerText;
                let questionScore   = parseInt(document.getElementById("questionScore_"+id).innerText.split(" ")[3]);

                $("#edit_question_id").val(id);
                $("#Edit_Question_Text_Content").text(questionText);
                $("#edit_question_text").val(questionText);
                $("#Edit_Question_Answer_Content").text(questionAnswer);
                $("#edit_question_answer").val(questionAnswer);
                $("#edit_max_score").val(questionScore);

            });

            $(".delete-question-btn").click(function (e) { 
                e.preventDefault();
                let id = $(this).attr("id").split("_")[1];
                let questionText    = document.getElementById("questionText_"+id).innerText;
                let questionAnswer  = document.getElementById("questionAnswer_"+id).innerText;
                let questionScore   = parseInt(document.getElementById("questionScore_"+id).innerText.split(" ")[3]);

                $("#delete_question_id").val(id);
                $("#delete_question_text").text(questionText);
                $("#delete_question_answer").text(questionAnswer);
                $("#delete_question_score").text(questionScore);

            });
        });
    </script>
@endsection