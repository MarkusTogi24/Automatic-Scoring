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
        <p id="user_ID" class="hidden">{{ Auth::user()->id }}</p>
        <div class="relative">
            <button type="button" x-on:click="showExitPopUp = true" class="block xl:flex xl:items-center xl:gap-2 text-white hover:text-gray-200">
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </span>
                <span class="hidden xl:block xl:m-0">Kembali</span>
            </button>
        </div>
        <p class="block m-0 text-sm xl:text-base xl:text-center">{{ $exam->name }} - {{ $classroom->name }}</p>
        {{-- <div class="hidden xl:block"> --}}
        <div class="hidden">
            <p class="hidden" id="end_time">{{ $exam->end_time }}</p>
            <p id="timerContainer" class="tracking-wide text-right">
                <span>Sisa Waktu :</span>
                <span id="timer">00:00:00</span>
            </p>
        </div>
    </nav>

    <div class="flex xl:hidden fixed z-[999] top-[4.24rem] w-full bg-primary-10 text-primary py-2 px-4 justify-between items-center">
        <div class="relative" x-data="{ showMobilePreview : false }">
            <button class="w-fit flex items-center gap-2" x-on:click="showMobilePreview =! showMobilePreview">
                <span class="block m-0 text-sm">Semua soal</span>
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
            <div class="fixed z-[1000] top-[6.5rem] w-fit h-fit max-h-[calc(50vh)] overflow-y-auto custom-scrollbar rounded-md p-3 border-2 border-primary bg-white drop-shadow"
                x-cloak x-show="showMobilePreview" x-on:click.outside="showMobilePreview = false">
                <div class="w-fit grid grid-cols-5 items-start gap-3 h-fit">
                    @for ($i = 0; $i < $questions->count(); $i++)
                        @if ($i == 0)
                            <button type="button" id="mobileQuestionBtn_{{ $i }}" disabled x-on:click="showMobilePreview = false"
                                class="question-btn number-question-btn flex w-9 h-9 rounded items-center justify-center border border-primary activeNumberBtn">
                                <p class="block m-0 text-sm font-semibold">{{ $i+1 }}</p>
                            </button>
                        @else
                            <button type="button" id="mobileQuestionBtn_{{ $i }}" x-on:click="showMobilePreview = false"
                                class="question-btn number-question-btn flex w-9 h-9 rounded items-center justify-center border border-primary
                                    {{ $questions[$i]->answer == "" ? 'emptyNumberBtn' : 'filledNumberBtn' }}">
                                <p class="block m-0 text-sm font-semibold">{{ $i+1 }}</p>
                            </button>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
        <p id="mobileTimerContainer" class="hidden tracking-wide text-sm text-right">
            <span>Sisa Waktu :</span>
            <span id="mobileTimer">00:00:00</span>
        </p>
    </div>

    <main class="relative w-full ">
        <div class="hidden xl:block fixed z-[1000] top-[4.25rem] h-[calc(100vh-4.25rem)] w-fit border-r-4 border-blue-300 bg-blue-50 bg-opacity-75 p-4">
            <p class="block m-0 mb-4 font-semibold text-primary">Pratinjau seluruh soal</p>
            <div class="relative w-fit h-fit max-h-[calc(100vh-4.25rem-3rem-1.5rem)] overflow-y-auto custom-scrollbar rounded-md p-3 bg-white border-2 border-primary">
                <div class="w-fit grid grid-cols-5 items-start gap-3 h-fit">
                    @for ($i = 0; $i < $questions->count(); $i++)
                        @if ($i==0)
                            <button type="button" id="questionBtn_{{ $i }}" disabled
                                class="question-btn number-question-btn flex w-10 h-10 rounded items-center justify-center border border-primary activeNumberBtn">
                                <p class="block m-0 text-sm font-semibold">{{ $i+1 }}</p>
                            </button>
                        @else
                            <button type="button" id="questionBtn_{{ $i }}" 
                                class="question-btn number-question-btn flex w-10 h-10 rounded items-center justify-center border border-primary
                                    {{ $questions[$i]->answer == "" ? 'emptyNumberBtn' : 'filledNumberBtn' }}">
                                <p class="block m-0 text-sm font-semibold">{{ $i+1 }}</p>
                            </button>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <div class="absolute top-[6.5rem] xl:top-[4.25rem] min-h-[calc(100vh-6.5rem)] xl:min-h-[calc(100vh-4.25rem)] w-full xl:w-[calc(100vw-6.5rem-12.5rem-31px)] xl:left-[calc(19rem+14px)] p-4">
            {{-- HIDDEN QUESTION DATA --}}
            <div class="text-xs hidden" id="questionsData">
                <p id="questionCount">{{ $questions->count() }}</p>
                <p id="user_id">{{ Auth::user()->id }}</p>
                <p id="exam_id">{{ $exam->id }}</p>
                @for ($i = 0; $i<$questions->count(); $i++)
                    <small>question_id : {{ $i }}</small>
                    <p id="question_{{ $i }}_id">{{ $questions[$i]->id }}</p>
                    <p id="question_{{ $i }}_question">{{ $questions[$i]->question }}</p>
                    <p id="question_{{ $i }}_answer_id">{{ $questions[$i]->answer_id }}</p>
                    <p id="question_{{ $i }}_answer">{{ $questions[$i]->answer }}</p>
                    <br>
                @endfor
            </div>
            <div id="questionContainer">
                <p class="block m-0 mb-4 xl:mb-6 text-gray-900 text-sm lg:text-base whitespace-pre-line">1. {{ $questions[0]->question }}</p>
                <div class="w-full mb-4 xl:mb-6">
                    <input type="hidden" name="answer_id" value="{{ $questions[0]->answer_id == null ? '' : $questions[0]->answer_id }}">
                    <input type="hidden" name="question_id" value="{{ $questions[0]->id }}">
                    <textarea id="answer_0" name="answer" class="answer hidden" >
                        {{ $questions[0]->answer == null ? '' : $questions[0]->answer }}
                    </textarea>
                    
                    <span id="Answer_Content_0" 
                        class="answer-content overflow-hidden min-h-[7.5rem] xl:min-h-[9rem] border-2 border-primary-20 text-primary-50 text-sm lg:text-base rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 lg:p-3 focus:ring-125 outline-none"
                        role="textbox" contenteditable>
                        {{ $questions[0]->answer == null ? 'Ketikkan jawaban anda' : $questions[0]->answer }}
                    </span>
                </div>
                <div class="w-full flex justify-end items-center">
                    <button type="button" id="nextQuestion_1" class="question-btn block w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- SUBMIT EXAM ANSWERS POP UP -->
    <div class="fixed z-[2220] inset-0" x-cloak x-show="showEndExamPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5 py-10 xl:py-12 flex flex-col justify-center items-center">
                <div class="w-fit text-warning mb-6 xl:mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="w-full text-center mb-8 xl:mb-10">
                    <p class="text-base xl:text-lg text-gray-900 font-semibold tracking-wide">
                        Anda yakin ingin mengakhiri ujian?
                    </p>
                </div>
                <div class="w-full flex justify-center items-center gap-4">
                    <button type="button" x-on:click="showEndExamPopUp = false" class="block w-36 text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm px-5 py-2.5 text-center">
                        Kembali
                    </button>
                    <form action="{{ route('exam.save', [$classroom->id, $exam->id]) }}" method="POST">
                        @csrf @method('POST')
                        <div class="" id="additionalFormFields"></div>
                        <button type="submit" class="block w-36 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                            Akhiri Ujian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- QUIT EXAM POP UP -->
    <div class="fixed z-[2220] inset-0" x-cloak x-show="showExitPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5 py-10 xl:py-12 flex flex-col justify-center items-center">
                <div class="w-fit text-warning mb-6 xl:mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="w-full text-center mb-4 xl:mb-6">
                    <p class="text-base xl:text-lg text-gray-900 font-semibold tracking-wide">
                        Anda yakin ingin meninggalkan ujian?
                    </p>
                </div>
                <div class="w-full text-center mb-8 xl:mb-10">
                    <p class="text-sm xl:text-base text-gray-900">
                        Jawaban anda saat ini akan tersimpan, namun sisa waktu akan terus berkurang.
                    </p>
                </div>
                <div class="w-full flex justify-center items-center gap-2 md:gap-4">
                    <button type="button" x-on:click="showExitPopUp = false" 
                        class="block w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs xl:text-sm py-2.5 text-center">
                        Lanjutkan Ujian
                    </button>
                    <a href="{{ route('exam.show', [$classroom->id, $exam->id]) }}"
                        class="block w-40 text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-xs xl:text-sm py-2.5 text-center">
                        Tinggalkan Ujian
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jQuery.min.js') }}"></script>
    <script>
        // Coloring the button if the question has answer
        function colorButton(index){
            if($("#questionBtn_"+index).hasClass("activeNumberBtn")){
                $("#questionBtn_"+index).attr("disabled", false);
                $("#questionBtn_"+index).removeClass("activeNumberBtn");
                $("#questionBtn_"+index).addClass("filledNumberBtn");
            }
            if($("#mobileQuestionBtn_"+index).hasClass("activeNumberBtn")){
                $("#mobileQuestionBtn_"+index).attr("disabled", false);
                $("#mobileQuestionBtn_"+index).removeClass("activeNumberBtn");
                $("#mobileQuestionBtn_"+index).addClass("filledNumberBtn");
            }
        }

        // Reloading the data
        function refreshData(questions_data){
            // console.log(questions_data);

            $("#questionsData").empty();

            let content = ""
                + "<p id=\"questionCount\">"+questions_data.length+"</p>"
                + "<p id=\"user_id\">"+document.getElementById("user_ID").innerText+"</p>"
                + "<p id=\"exam_id\">"+questions_data[0].exam_id+"</p>"
            + "";

            for(let i=0; i<questions_data.length; i++){
                content += "<p id=\"question_"+i+"_id\">"+questions_data[i].id+"</p>";
                content += "<p id=\"question_"+i+"_question\">"+questions_data[i].question+"</p>";
                (questions_data[i].answer_id == null) ? 
                    content += "<p id=\"question_"+i+"_answer_id\"></p>":
                    content += "<p id=\"question_"+i+"_answer_id\">"+questions_data[i].answer_id+"</p>";
                (questions_data[i].answer == null) ? 
                    content += "<p id=\"question_"+i+"_answer\"></p>":
                    content += "<p id=\"question_"+i+"_answer\">"+questions_data[i].answer+"</p>";
                content += "<br/>";
            }

            $("#questionsData").html(content);
            
        }

        // Loading the current question to the page
        function loadQuestion(index, question_id, question, answer_id, answer, answer_content, questionCount ) {
            // Clearing the question container section
            $("#questionContainer").empty();

            let idx = parseInt(index);

            let content = ""
                + "<p class=\"block m-0 mb-4 xl:mb-6 text-gray-900 text-sm lg:text-base whitespace-pre-line\">" + (idx+1) + ". " + question + "</p>"
                + "<div class=\"w-full mb-4 xl:mb-6\">"
                    + "<input type=\"hidden\" name=\"answer_id\" value=\"" + answer_id + "\">"
                    + "<input type=\"hidden\" name=\"question_id\" value=\"" + question_id + "\">"
                    + "<textarea id=\"answer_" + index + "\" name=\"answer\" class=\"answer hidden\">"
                        + answer
                    + "</textarea>"
                    + "<span id=\"Answer_Content_" + index + "\""
                        + "class=\"answer-content overflow-hidden min-h-[7.5rem] xl:min-h-[9rem] border-2 border-primary-20 text-primary-50 text-sm lg:text-base rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 lg:p-3 focus:ring-125 outline-none\""
                        + "role=\"textbox\" contenteditable>"
                        + answer_content
                    + "</span>"
                + "</div>"
            + "";

            if(index==0){
                content += ""
                    + "<div class=\"w-full flex justify-end items-center\">"
                        + "<button type=\"button\" id=\"nextQuestion_1\" class=\"question-btn block w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center\">"
                            + "Selanjutnya"
                        + "</button>"
                    + "</div>"
                + "";
            }else if(idx == questionCount-1){
                content += ""
                    + "<div class=\"w-full flex justify-between items-center\">"
                        + "<button type=\"button\" id=\"prevQuestion_"+(questionCount-2)+"\" class=\"question-btn block w-40 text-primary bg-white hover:bg-primary-10 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-md text-sm px-5 py-2.5 text-center\">"
                            + "Sebelumnya"
                        + "</button>"
                        + "<button type=\"button\" id=\"submitExamAnswer\" x-on:click=\"showEndExamPopUp = true\" class=\"block w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold uppercase tracking-widest rounded-md text-base px-5 py-3 text-center\">"
                            + "SUBMIT"
                        + "</button>"
                    + "</div>"
                + "";
            }else{
                content += ""
                    + "<div class=\"w-full flex justify-between items-center\">"
                        + "<button type=\"button\" id=\"prevQuestion_"+(idx-1)+"\" class=\"question-btn block w-40 text-primary bg-white hover:bg-primary-10 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-md text-sm px-5 py-2.5 text-center\">"
                            + "Sebelumnya"
                        + "</button>"
                        + "<button type=\"button\" id=\"nextQuestion_"+(idx+1)+"\" class=\"question-btn block w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center\">"
                            + "Selanjutnya"
                        + "</button>"
                    + "</div>"
                + "";
            }
            // Appending content to the container
            $("#questionContainer").html(content);
        }

        // REDIRECT ACTIONS
        function redirectAction(response, this_id){
            if(response["exam_status"] === "closed"){
                window.location.href = "/mata-pelajaran/"+response["classroom_id"]+"/ujian/"+response["exam_id"]+"/exam-closed";
            }else{
                refreshData(response);
                colorButton(this_id);
            }
        }

        // Load loading indicator on AJAX process
        function loadLoadingIndicator() {
            $("#questionContainer").empty();

            let loading_indicator = ""
                + "<div class=\"w-fit m-auto h-[calc(100vh-9rem)] xl:h-[calc(100vh-6.75rem)] flex flex-col justify-center items-center gap-4\">"
                    + "<div class=\"h-9 w-9\">"
                        + "<img src=\"{{ asset('image/loading.png') }}\" class=\"w-full h-auto animate-spin\" alt=\"\">"
                    + "</div>"
                    + "<p class=\"block m-0 text sm text-primary\">Loading...</p>"
                + "</div>"
            + "";

            $("#questionContainer").html(loading_indicator);
        }

        // MAIN PROCESS
        function processQuestion(index, questionCount, exam_id){
            // Getting question data by the index
            let question_id     = document.getElementById("question_"+index+"_id").innerText;
            let question        = document.getElementById("question_"+index+"_question").innerText;
            let answer_id       = document.getElementById("question_"+index+"_answer_id").innerText;
            let answer          = document.getElementById("question_"+index+"_answer").innerText;
            let answer_content  = "";
            (answer == "") ? answer_content += "Ketikkan jawaban anda" : answer_content += answer;

            // Getting previous data before button clicked
            let post_id             = $("input[name=answer_id]").val();
            let post_question_id    = $("input[name=question_id]").val();
            let post_student_id     = $("#user_id").text();
            let post_answer         = $("textarea").val().trim();
            let this_id             = $("textarea").attr("id").split("_")[1];
            let old_answer          = document.getElementById("question_"+this_id+"_answer").innerText.trim();

            // AJAX PROCESS
            if(post_answer != old_answer){
                if (post_id == ""){
                    $.ajax({
                        type    : "POST",
                        url     : "/store-student-answer/"+exam_id,
                        data    : {
                            question_id : post_question_id,
                            student_id  : post_student_id,
                            answer      : post_answer,
                        },
                        dataType: 'json',
                        beforeSend : function(){
                            loadLoadingIndicator();
                        },
                        complete: function(){
                            loadQuestion(index, question_id, question, answer_id, answer, answer_content, questionCount);
                        },
                        success: function (response){
                            redirectAction(response, this_id);
                        },
                    });
                } else {
                    $.ajax({
                        type    : "POST",
                        url     : "/update-student-answer/"+exam_id,
                        data    : {
                            id          : post_id,
                            question_id : post_question_id,
                            student_id  : post_student_id,
                            answer      : post_answer,
                        },
                        dataType: 'json',
                        beforeSend : function(){
                            loadLoadingIndicator();
                        },
                        complete: function(){
                            loadQuestion(index, question_id, question, answer_id, answer, answer_content, questionCount);
                        },
                        success: function(response){
                            redirectAction(response, this_id);
                        },
                    });
                }
            }else{
                loadQuestion(index, question_id, question, answer_id, answer, answer_content, questionCount);
                if(post_answer == ""){
                    if($("#questionBtn_"+this_id).hasClass("activeNumberBtn")){
                        $("#questionBtn_"+this_id).attr("disabled", false);
                        $("#questionBtn_"+this_id).removeClass("activeNumberBtn");
                        $("#questionBtn_"+this_id).addClass("emptyNumberBtn");
                    }
                    if($("#mobileQuestionBtn_"+this_id).hasClass("activeNumberBtn")){
                        $("#mobileQuestionBtn_"+this_id).attr("disabled", false);
                        $("#mobileQuestionBtn_"+this_id).removeClass("activeNumberBtn");
                        $("#mobileQuestionBtn_"+this_id).addClass("emptyNumberBtn");
                    }
                }else{
                    colorButton(this_id);
                }
            }

            // Disabling & coloring active number button
            $("#mobileQuestionBtn_"+index).attr("disabled", true);
            if($("#mobileQuestionBtn_"+index).hasClass("emptyNumberBtn")){
                $("#mobileQuestionBtn_"+index).removeClass("emptyNumberBtn");
                $("#mobileQuestionBtn_"+index).addClass("activeNumberBtn");
            }else if($("#mobileQuestionBtn_"+index).hasClass("filledNumberBtn")){
                $("#mobileQuestionBtn_"+index).removeClass("filledNumberBtn");
                $("#mobileQuestionBtn_"+index).addClass("activeNumberBtn");
            }
            // Disabling & coloring active mobile number button
            $("#questionBtn_"+index).attr("disabled", true);
            if( $("#questionBtn_"+index).hasClass("emptyNumberBtn") ){
                $("#questionBtn_"+index).removeClass("emptyNumberBtn");
                $("#questionBtn_"+index).addClass("activeNumberBtn");
            }else if( $("#questionBtn_"+index).hasClass("filledNumberBtn") ){
                $("#questionBtn_"+index).removeClass("filledNumberBtn");
                $("#questionBtn_"+index).addClass("activeNumberBtn");
            }
        }

        $(document).ready(function () {

            // TIMER
            let endTime = $("#end_time").text();
            let countDownTime = new Date(endTime);

            let timer = setInterval(() => {
                let now         = new Date();
                var distance    = countDownTime - now;

                let seconds = Math.floor(distance / 1000);
                let minutes = Math.floor(seconds / 60);
                let hours   = Math.floor(minutes / 60);
                let days    = Math.floor(hours / 24);

                let showHours = "";
                (hours<10) ? showHours += "0" + hours : showHours += hours;
                let showMinutes = "";
                (minutes%60<10) ? showMinutes += "0" + minutes%60 : showMinutes += minutes%60;
                let showSeconds = "";
                (seconds%60<10) ? showSeconds += "0" + seconds%60 : showSeconds += seconds%60;

                let timerString = showHours + ":" + showMinutes + ":" + showSeconds;
                $("#timer").html(timerString);
                $("#mobileTimer").html(timerString);

                if (distance < 0) {
                    clearInterval(timer);
                    $("#timerContainer").html("<span class=\"font-bold tracking-wide text-white uppercase\">WAKTU HABIS</span>");
                    $("#mobileTimerContainer").html("<span class=\"font-bold tracking-wide text-primary uppercase\">WAKTU HABIS</span>");
                }
            }, 1000);

            // AJAX SETUP
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // QUESTION NAVIGATION BY NUMBER BUTTON
            $(".question-btn").click(function (e) {
                e.preventDefault();

                // Disabling & coloring clicked button
                $(this).attr("disabled", true);
                if($(this).hasClass("emptyNumberBtn")){
                    $(this).removeClass("emptyNumberBtn");
                    $(this).addClass("activeNumberBtn");
                }
                else if($(this).hasClass("filledNumberBtn")){
                    $(this).removeClass("filledNumberBtn");
                    $(this).addClass("activeNumberBtn");
                }
                
                // Getting question index in the Array
                let index           = $(this).attr("id").split("_")[1];
                let questionCount   = parseInt(document.getElementById("questionCount").innerText);
                let exam_id         = document.getElementById("exam_id").innerText;

                // MAIN PROCESS
                processQuestion(index, questionCount, exam_id);
            });

            // QUESTION NAVIGATION BY NEXT & PREV BUTTON
            $("#questionContainer").on("click", ".items-center .question-btn", function(e){
                e.preventDefault();

                // Getting question index in the Array
                let index = $(this).attr("id").split("_")[1];
                let questionCount = parseInt(document.getElementById("questionCount").innerText);
                let exam_id       = document.getElementById("exam_id").innerText;
                
                // MAIN PROCESS
                processQuestion(index, questionCount, exam_id);
            });

            // SUBMIT BUTTON
            $("#questionContainer").on("click", ".items-center #submitExamAnswer", function(e){
                e.preventDefault();

                let questionCount = parseInt(document.getElementById("questionCount").innerText);
                let index = questionCount-1;
                
                // Getting question data by the index
                // let question_id         = document.getElementById("question_"+index+"_id").innerText;
                // let question            = document.getElementById("question_"+index+"_question").innerText;
                // let answer_id           = document.getElementById("question_"+index+"_answer_id").innerText;
                // let answer              = document.getElementById("question_"+index+"_answer").innerText;
                // let answer_content      = "";
                // (answer == "") ? answer_content += "Ketikkan jawaban anda" : answer_content += answer;

                let post_id             = $("input[name=answer_id]").val();
                let post_question_id    = $("input[name=question_id]").val();
                let post_student_id     = $("#user_id").text();
                let post_answer         = $("textarea").val().trim();
                let this_id             = $("textarea").attr("id").split("_")[1];
                let old_answer          = document.getElementById("question_"+this_id+"_answer").innerText.trim();

                // console.log("Pos ID     : "+post_id);
                // console.log("Pos Q ID   : "+post_question_id);
                // console.log("Pos S ID   : "+post_student_id);
                // console.log("Pos A      : "+post_answer);
                // console.log("This ID    : "+this_id);
                // console.log("Old Answer : "+old_answer);
                
                $("#additionalFormFields").empty();

                if (post_id == "" && post_answer != ""){
                    console.log("SIMPAN BARU")
                    let content = ""
                        + "<input type=\"hidden\" name=\"answer_text\" value=\""+post_answer+"\">"
                        + "<input type=\"hidden\" name=\"question_id\" value=\""+post_question_id+"\">"
                        + "<input type=\"hidden\" name=\"student_id\" value=\""+post_student_id+"\">"
                    + "";
                    $("#additionalFormFields").html(content);

                }else if(post_id != "" && post_answer != old_answer){
                    console.log("UPDATE YANG SUDAH ADA")
                    let content = ""
                        + "<input type=\"hidden\" name=\"answer_id\" value=\""+post_id+"\">"
                        + "<input type=\"hidden\" name=\"answer_text\" value=\""+post_answer+"\">"
                        + "<input type=\"hidden\" name=\"question_id\" value=\""+post_question_id+"\">"
                        + "<input type=\"hidden\" name=\"student_id\" value=\""+post_student_id+"\">"
                    + "";
                    $("#additionalFormFields").html(content);
                }
            });

            // Supporting auto-growing text-area
            $("#questionContainer").on("keyup", ".w-full .answer-content", function(event) {
                let index = $(this).attr("id").split("_")[2];
                let text = document.getElementById("Answer_Content_"+index).innerText;
                $("#answer_"+index).val(text);
            });

            // Supporting placeholder in auto-growing text-area
            $("#questionContainer").on("focusin", ".w-full .answer-content", function(event) {
                let text = $(this).text().trim();
                if(text == "Ketikkan jawaban anda"){
                    $(this).text(""); 
                }
            });

            // Supporting placeholder in auto-growing text-area
            $("#questionContainer").on("focusout", ".w-full .answer-content", function(event) {
                let text = $(this).text().trim();
                if(text == ""){
                    $(this).text("Ketikkan jawaban anda"); 
                }
            });
        });
    </script>
</body>
</html>
