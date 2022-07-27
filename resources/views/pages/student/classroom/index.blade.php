@extends('layouts.master')

@section('content')
<div class="w-full lg:w-[calc(100vw-336px)] grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">

    <div class="relative w-full p-2 bg-white rounded-md drop-shadow-md overflow-hidden">
        <div class="w-full aspect-video bg-[url('/image/BG1.png')] bg-cover bg-center rounded-md mb-2">
        </div>
        <div class="w-full h-16 relative px-2">
            <a href="" class="flex flex-col text-primary hover:text-primary-70 font-medium tracking-wide">
                <span class="block m-0">Bahasa Indonesia</span>
                <span class="block m-0">11 IPA 1</span>
            </a>
            <div class="absolute rounded-full w-[72px] h-[72px] bottom-10 right-5 overflow-hidden">
                <img src="{{ asset('image/dummy_pp.png') }}" class="w-full h-auto" alt="TEACHER">
            </div>
        </div>
    </div>

    <div class="relative w-full p-2 bg-white rounded-md drop-shadow-md overflow-hidden">
        <div class="w-full aspect-video bg-[url('/image/BG2.png')] bg-cover bg-center rounded-md mb-2">
        </div>
        <div class="w-full h-16 relative px-2">
            <a href="" class="flex flex-col text-primary hover:text-primary-70 font-medium tracking-wide">
                <span class="block m-0">Matematika Diskrit</span>
                <span class="block m-0">11 IPA 1</span>
            </a>
            <div class="absolute rounded-full w-[72px] h-[72px] bottom-10 right-5 overflow-hidden">
                <img src="{{ asset('image/dummy_pp.png') }}" class="w-full h-auto" alt="TEACHER">
            </div>
        </div>
    </div>

    <div class="relative w-full p-2 bg-white rounded-md drop-shadow-md overflow-hidden">
        <div class="w-full aspect-video bg-[url('/image/BG3.png')] bg-cover bg-center rounded-md mb-2">
        </div>
        <div class="w-full h-16 relative px-2">
            <a href="" class="flex flex-col text-primary hover:text-primary-70 font-medium tracking-wide">
                <span class="block m-0">Ilmu Pengetahuan Alam</span>
                <span class="block m-0">11 IPA 1</span>
            </a>
            <div class="absolute rounded-full w-[72px] h-[72px] bottom-10 right-5 overflow-hidden">
                <img src="{{ asset('image/dummy_pp.png') }}" class="w-full h-auto" alt="TEACHER">
            </div>
        </div>
    </div>

    <div class="relative w-full p-2 bg-white rounded-md drop-shadow-md overflow-hidden">
        <div class="w-full aspect-video bg-[url('/image/BG2.png')] bg-cover bg-center rounded-md mb-2">
        </div>
        <div class="w-full h-16 relative px-2">
            <a href="" class="flex flex-col text-primary hover:text-primary-70 font-medium tracking-wide">
                <span class="block m-0">Tutorial Mewarnai</span>
                <span class="block m-0">11 IPA 1</span>
            </a>
            <div class="absolute rounded-full w-[72px] h-[72px] bottom-10 right-5 overflow-hidden">
                <img src="{{ asset('image/dummy_pp.png') }}" class="w-full h-auto" alt="TEACHER">
            </div>
        </div>
    </div>

</div>
@endsection
