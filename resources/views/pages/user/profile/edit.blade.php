@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/cropper.min.css') }}">
@endsection
@section('content')

<div class="w-full">

    @include('components.session-alert')

    <div class="w-full flex items-center justify-between mb-4 xl:mb-6">
        <a href="{{ route('profile.index') }}" class="flex items-center gap-2 text-primary hover:text-primary-70">
            <span class="block m-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </span>
            <span class="text-lg xl:text-xl">Kembali</span>
        </a>
        <p class="block m-0 text-lg xl:text-xl font-bold text-primary tracking-wider">
            Edit Profil
        </p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('POST')
        <div class="bg-white rounded-md xl:rounded-lg overflow-hidden drop-shadow p-4 md:p-6 lg:p-10 xl:p-16 w-full flex flex-col lg:flex-row lg:items-center gap-4 md:gap-6 lg:gap-10 xl:gap-16">
            
            <!-- PROFILE PICTURE -->
            <div>
                <label for="profile_picture" 
                    class="relative block w-56 m-auto lg:m-0 lg:flex lg:flex-col lg:items-center lg:justify-center rounded-full overflow-hidden bg-white border-2 border-primary-30 focus:ring-primary-70 focus:border-primary-70 p-4 group">
                    <img id="cropped_image" 
                        src="{{ Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : asset('image/PP.jpg') }}" class="w-full h-auto rounded-full" alt="FOTO PROFIL">
                    <div class="absolute bottom-0 w-full -mx-4 bg-white bg-opacity-75 lg:bg-opacity-90 font-semibold lg:font-normal text-sm text-center py-4 text-primary-50 lg:translate-y-full lg:group-hover:translate-y-0 lg:transition-all lg:ease-in-out lg:duration-300">
                        <p>Klik untuk mengubah <br> foto profil</p>
                    </div>
                </label>
                {{-- {{ Auth::user()->profile_picture }} --}}
                <p id="file_error" class="hidden w-56 m-auto mt-1 mb-4 text-xs font-medium text-danger text-center"></p>
            </div>
            
            <div class="w-full lg:w-fit px-4 lg:p-0">
                <!-- PROFILE PICTURE -->
                <input type="file" id="profile_picture" name="profile_picture" class="hidden">

                <!-- FULL NAME -->
                <label for="name" class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Nama Lengkap
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') ? old('name') : Auth::user()->name }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('name') 'mb-0' @else 'mb-4' @enderror" 
                    autocomplete="off" placeholder="Nama Lengkap">
                @error('name')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror

                <!-- EMAIL ADDRESS -->
                <label for="email" class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Alamat Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') ? old('email') : Auth::user()->email }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('email') 'mb-0' @else 'mb-4' @enderror" 
                    autocomplete="off" placeholder="Alamat Email">
                @error('email')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
                    
                <!-- OLD PASSWORD -->
                <label for="old_password" class="block m-0 mb-1 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Kata Sandi Saat Ini
                </label>
                <input type="password" id="old_password" name="old_password" value="{{ old('old_password') }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('old_password') 'mb-0' @else 'mb-4' @enderror" 
                    autocomplete="off" placeholder="Kata Sandi Saat Ini">
                @error('old_password')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
                
                <!-- NEW PASSWORD -->
                <label for="new_password" class="block m-0 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Kata Sandi Baru
                </label>
                <small class="text-gray-500 block m-0 mb-1 font-bold">Opsional</small>
                <input type="password" id="new_password" name="new_password" value="{{ old('new_password') }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('new_password') 'mb-0' @else 'mb-4' @enderror" 
                    autocomplete="off" placeholder="Kata Sandi Baru">
                @error('new_password')
                    <p class="block mt-1 mb-4 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
                
                <!-- PASSWORD CONFIRMATION -->
                <label for="password" class="block m-0 text-gray-700 font-bold tracking-wide text-lg xl:text-xl">
                    Konfirmasi Kata Sandi Baru
                </label>
                <small class="text-gray-500 block m-0 mb-1 font-bold">Opsional</small>
                <input type="password" id="password_confirmation" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full lg:w-72 p-2.5 custom-placeholder @error('new_password_confirmation') 'mb-0' @else 'mb-6 lg:mb-0' @enderror" 
                    autocomplete="off" placeholder="Konfirmasi Kata Sandi Baru">
                @error('new_password_confirmation')
                    <p class="block mt-1 mb-6 lg:mb-0 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex justify-end py-4">
            <button type="submit" class="w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                SIMPAN PERUBAHAN
            </button>
        </div>
    </form>
</div>
<div id="modal" class="fixed z-[2220] inset-0 hidden">
    <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
        <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-3">
            <!-- POP UP HEADER -->
            <div class="flex justify-between items-center mb-4">
                <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                    Sesuaikan Gambar 
                </p>
                <button id="closeModal" type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                    x-on:click="showAddAccountPopUp = false">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- POP UP BODY -->
            <div id="popUpBody" class="w-full aspect-square mb-4">
            </div>
            <div class="w-full">
                <button id="crop_image" type="button" class="w-full text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                    {{ __('Pilih Gambar') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/cropper.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            let cropper;
            let originalSrc = $("#cropped_image").attr("src");

            // INPUT FILE CHANGE EVENT
            $("#profile_picture").on("change", function (event) { 
                let file = event.target.files[0];
                let done = (url) => {
                    let img = "<img id=\"preview\" src=\""+url+"\" class=\"w-full aspect-square\">";
                    $("#popUpBody").empty();
                    $("#popUpBody").html(img);
                    let image = document.getElementById("preview");
                    cropper = new Cropper(image, {
                        aspectRatio     : 1,
                        viewMode        : 3,
                        minCropBoxWidth : 240,
                        minCropBoxHeight: 240,
                    });
                    $("#modal").removeClass("hidden");
                };
                
                if(file){
                    if(file.type.split("/")[0] == "image"){
                        if(file.size <= 1048576){
                            if($("#file_error").hasClass("block")){
                                $("#file_error").removeClass("block");
                                $("#file_error").addClass("hidden");
                            }
                            let reader = new FileReader();
                            reader.onload = (event) => {
                                done(reader.result);
                            };
                            reader.readAsDataURL(file);
                        }else{
                            if($("#file_error").hasClass("hidden")){
                                $("#file_error").removeClass("hidden");
                                $("#file_error").addClass("block");
                            }
                            $("#file_error").text("Ukuran maksimal file yang dipilih adalah 1MB.");
                            $(this).val("");
                        }
                    }else{
                        if($("#file_error").hasClass("hidden")){
                            $("#file_error").removeClass("hidden");
                            $("#file_error").addClass("block");
                        }
                        $("#file_error").text("Hanya dapat menerima file gambar JPG, JPEG, atau PNG.");
                        $(this).val("");
                    }
                }
            });

            // FORCE-CLOSE THE MODAL
            $("#closeModal").click(function (event) { 
                event.preventDefault();
                $("#modal").addClass("hidden");
                $("#popUpBody").empty();
                $("#profile_picture").val("");
                $("#cropped_image").attr("src", originalSrc);
                cropper = null;
            });

            // USER CLICK THE "CROP" BUTTON
            $("#crop_image").on("click", function () {
                let canvas = cropper.getCroppedCanvas({
                    width   : 400,
                    height  : 400,
                });

                canvas.toBlob(function(blob){
                    url = URL.createObjectURL(blob);
                    $("#cropped_image").attr("src", url);
                    let newFile = new File([blob], 'TempFileName.jpg', {type:"image/jpeg", lastModified:new Date().getTime()}, 'utf-8');
                    let container = new DataTransfer(); 
                    container.items.add(newFile);
                    $("#profile_picture").val("")
                    document.querySelector('#profile_picture').files = container.files;
                    $("#modal").addClass("hidden");
                });
            });
        });
    </script>
@endsection