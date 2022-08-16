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
<body x-data="{
        showEndExamPopUp : false,
        showExitPopUp : false,
    }">
    <nav class="fixed z-[999] top-0 w-full bg-primary-50 text-white h-[4.25rem] flex justify-between xl:grid xl:grid-cols-3 items-center px-4">
        <div class="relative">
            <a href="{{ route('exam.show', [$classroom->id, $exam->id]) }}" class="block xl:flex xl:items-center xl:gap-2 text-white hover:text-gray-200">
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </span>
                <span class="hidden xl:block xl:m-0">Kembali</span>
            </a>
        </div>
        <p class="block m-0 text-sm xl:text-base xl:text-center">{{ $exam->name }} - {{ $classroom->name }}</p>
    </nav>

    <main class="relative w-full">
        <div class="absolute top-[4.25rem] min-h-[calc(100vh-4.25rem)] w-full p-4">
            <div class="w-full md:w-11/12 md:m-auto lg:w-2/3 flex justify-between items-center p-4 xl:p-8 bg-primary-50">
                <div class="w-3/4 lg:w-4/5 text-white">
                    <p class="block m-0 text-base lg:text-lg xl:text-xl font-bold tracking-wider mb-4 xl:mb-6">
                        Detail hasil ujian
                    </p>
                    <p class="block m-0 text-sm lg:text-base xl:text-lg mb-1">
                        Total nilai
                    </p>
                    @php
                        $total_score = 0;
                        $total_max_score = 0;
                        foreach ($questions as $question) {
                            $total_score += $question->score;
                            $total_max_score += $question->max_score;
                        }
                    @endphp
                    <p class="block m-0 text-sm lg:text-base xl:text-lg font-semibold">
                        {{ $total_score }}/{{ $total_max_score }}
                    </p>
                </div>
                <div class="w-1/4 lg:w-1/5">
                    <img src="{{ asset('image/TUT-WURI-HANDAYANI.png') }}" alt="LOGO TUT WURI" class="block w-11/12 lg:w-4/5 m-auto h-auto">
                </div>
            </div>

            @foreach ($questions as $question)
                <div class="w-full md:w-11/12 md:m-auto lg:w-2/3 mt-6 md:mt-8">
                    <p class="block m-0 text-sm lg:text-base xl:text-lg font-medium mb-3 xl:mb-4">
                        {{ $loop->index + 1 }}. {{ $question->question }}
                    </p>
                    <div class="w-full p-2 xl:p-4 rounded lg:rounded-md border {{ $question->answer ? 'border-primary-50' : 'border-danger' }} mb-3 xl:mb-4">
                        <p class="block m-0 text-sm lg:text-base xl:text-lg {{ $question->answer ? 'text-primary-50' : 'text-danger' }}">
                            {{ $question->answer ? $question->answer : 'Anda tidak menjawab pertanyaan ini' }}
                        </p>
                    </div>
                    @if ($question->score === null || $question->score == 0 )
                        <p class="block m-0 text-sm lg:text-base xl:text-lg font-bold text-danger">
                            Skor : 0/{{ $question->max_score }}
                        </p>
                    @else
                        <p class="block m-0 text-sm lg:text-base xl:text-lg font-bold text-gray-700">
                            Skor : {{ $question->score }}/{{ $question->max_score }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jQuery.min.js') }}"></script>
</body>
</html>
