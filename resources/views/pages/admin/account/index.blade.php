@extends('layouts.master')

@section('content')

<div class="w-full" x-data="{
    showAddAccountPopUp     : @if($errors->hasBag('create_account')) true @else false @endif,
    showEditAccountPopUp    : @if($errors->hasBag('edit_account')) true @else false @endif,
    showUploadAccountPopUp  : false,
    showDeleteAccountPopUp  : false,
}">
    @include('components.session-alert')

    <div class="flex flex-col gap-4 lg:flex-row lg:gap-0 justify-between items-center mb-4">

        <p class="block m-0 text-lg xl:text-xl font-bold text-primary tracking-wider w-full lg:w-fit text-left">
            Data Akun
        </p>

        <div class="flex justify-start lg:justify-end gap-2 lg:gap-4 w-full lg:w-fit">
            <button type="button" x-on:click="showAddAccountPopUp = true"
                class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                Tambah Akun
            </button>
            <button type="button" x-on:click="showUploadAccountPopUp = true"
                class="block text-sm lg:text-base py-1 px-4 lg:px-5 rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-blue-300 text-center text-white">
                Impor Akun
            </button>
        </div>
    </div>

    <!-- ACCOUNT TABLE -->
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-4">
        <table class="w-full text-sm text-left text-primary-50">
            <thead class="text-xs text-primary-70 uppercase bg-primary-10">
                <tr>
                    <th scope="col" class="py-3 px-6 text-center"> # </th>
                    <th scope="col" class="py-3 px-6"> Nama Lengkap </th>
                    <th scope="col" class="py-3 px-6"> Email </th>
                    <th scope="col" class="py-3 px-6 text-center"> Role </th>
                    <th scope="col" class="py-3 px-6 text-center"> Aksi </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 text-center">
                            {{($accounts->currentPage() - 1) * $accounts->perPage() + $loop->iteration}}
                        </td>
                        <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap" id="accountName_{{ $account->id }}">
                            {{ $account->name }}
                        </th>
                        <td class="py-4 px-6" id="accountEmail_{{ $account->id }}">
                            {{ $account->email }}
                        </td>
                        <td class="py-4 px-6 text-center" id="accountRole_{{ $account->id }}">
                            {{ $account->role }}
                        </td>
                        <td class="flex items-center py-4 px-6 space-x-3 justify-center">
                            <button type="button" id="editAccountBtn_{{ $account->id }}" x-on:click="showEditAccountPopUp = true"
                                class="edit-account-btn block w-16 py-1 text-xs rounded bg-primary hover:bg-primary-70 focus:ring-2 focus:outline-none focus:ring-primary-30 text-center text-white">
                                Edit
                            </button>
                            <button type="button" id="deleteAccountBtn_{{ $account->id }}" x-on:click="showDeleteAccountPopUp = true"
                            class="delete-account-btn block w-16 py-1 text-xs rounded bg-danger hover:bg-danger-70 focus:ring-2 focus:outline-none focus:ring-danger-30 text-center text-white">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $accounts->onEachSide(2)->links() }}

    <!-- "ADD ACCOUNT" POP UP -->
    <div class="fixed z-[2220] inset-0"
        x-cloak x-show="showAddAccountPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                <!-- POP UP HEADER -->
                <div class="flex justify-between items-center mb-4">
                    <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                        Tambah Akun 
                    </p>
                    <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                        x-on:click="showAddAccountPopUp = false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- POP UP BODY -->
                <form action="{{ route('admin.account.store') }}" method="POST">
                    @method('POST')
                    @csrf
                    <div class="w-full py-2 overflow-y-auto mb-4 custom-scrollbar">
                        <!-- FULL NAME -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Nama Lengkap') }}</p>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                            @error('name', 'create_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Email') }}</p>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                            @error('email', 'create_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ROLE -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Role') }}</p>
                            <select name="role" id="role" class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5">
                                <option selected disabled hidden>Pilih Role</option>
                                <option value="SISWA" @selected(old('role') === "SISWA") class="p-2.5">Siswa</option>
                                <option value="GURU" @selected(old('role') === "GURU")  class="p-2.5">Guru</option>
                            </select>
                            @error('role', 'create_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Kata Sandi') }}</p>
                            <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="Kata Sandi"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                            @error('password', 'create_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PASSWORD CONFIRMATION -->
                        <div class="">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Konfirmasi Kata Sandi') }}</p>
                            <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Konfirmasi Kata Sandi"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                                @error('password_confirmation', 'create_account')
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

    <!-- "UPLOAD ACCOUNT" POP UP FOR TEACHER -->
    <div class="fixed z-[2220] inset-0"
        x-cloak x-show="showUploadAccountPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                <!-- POP UP HEADER -->
                <div class="flex justify-between items-center mb-6">
                    <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                        Impor Data Akun
                    </p>
                    <button type="button" id="closeImportAccountModal" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                        x-on:click="showUploadAccountPopUp = false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>
                
                <form action="{{ route('admin.account.upload') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('POST')
                    <!-- POP UP BODY -->
                    <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar" 
                        x-data="{switchDisplay : true}">
                        <!-- CONTENT SWITCH TOGGLER BUTTON -->
                        <button type="button" x-on:click="switchDisplay =! switchDisplay"
                            class="flex items-center w-full bg-white text-primary font-semibold text-base py-2.5 justify-between"
                            x-bind:class="switchDisplay && 'border-b-2 border-primary mb-4' ">
                            <span>
                                <span x-show="switchDisplay">Lihat</span>
                                <span x-show="!switchDisplay">Tutup</span>
                                Panduan Impor Akun
                            </span>
                            <span x-bind:class="switchDisplay || 'rotate-90' ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <!-- IMPORT ACCOUNT GUIDANCE -->
                        <div class="mb-4 border border-primary rounded-lg p-4" x-show="!switchDisplay">
                            <p class="block mb-2 text-base font-bold text-gray-900">
                                Bagaimana cara untuk mengimpor akun?
                            </p>
                            <p class="block mb-2 text-sm text-gray-900">
                                <span class="font-semibold">Penting!</span> Pastikan anda mengunggah fail yang sesuai dengan templat yang telah disediakan. Silakan unduh dan baca terlebih dahulu fail <span class="font-semibold text-primary">Panduan Impor</span>, kemudian gunakan juga fail <span class="font-semibold text-success">Templat Excel</span> di bawah ini untuk memudahkan anda ketika ingin mengimpor akun, dan menghindari kemungkinan terjadinya kesalahan yang dapat memicu kerusakan pada sistem.
                                
                                {{-- <span class="font-semibold text-success">Templat Excel</span>
                                <span class="font-semibold"></span> --}}
                                {{-- Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mi dolor ultrices lectus et bibendum eget feugiat amet hendrerit. Tellus in tortor aliquam egestas. Nunc ut cursus velit nulla. Enim mattis sed scelerisque ut nec tempor vitae. Tincidunt placerat quisque velit, sed velit. Id metus malesuada in volutpat. Ac sodales mi mi magna diam. --}}
                            </p>
                            <div class="flex gap-4 justify-start items-center">
                                <div class="">
                                    <p class="block mb-2 text-base font-bold text-gray-900">
                                        Panduan Impor
                                    </p>
                                    <a href="{{ asset('file/Panduan Impor Akun.pdf') }}" class="flex items-center gap-3 w-fit text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm pl-3 pr-5 py-1.5" download>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </span>
                                        <span>Download</span>
                                    </a>
                                </div>
                                <div class="">
                                    <p class="block mb-2 text-base font-bold text-gray-900">
                                        Templat Excel
                                    </p>
                                    <a href="{{ asset('file/Templat Impor Akun.xlsx') }}" class="flex items-center gap-3 w-fit text-white bg-success hover:bg-success-70 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-md text-sm pl-3 pr-5 py-1.5" download>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </span>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- IMPORT ACCOUNT DRAG AND DROP -->
                        <div class="w-full mb-4 bg-primary-10" x-show="switchDisplay">
                            <label id="importAccountInputLabel" class="block cursor-pointer w-full text-center" for="accountFile">
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
                                        <p id="dropMsg" class="text-base text-white font-thin"></p>
                                    </div>
                                </div>
                            </label>
                            <input name="accountFile" id="accountFile" class="hidden" type="file">
                        </div>
                        <!-- IMPORT ACCOUNT FILE PREVIEW -->
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

    <!-- "EDIT ACCOUNT" POP UP -->
    <div class="fixed z-[2220] inset-0"
        x-cloak x-show="showEditAccountPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                <!-- POP UP HEADER -->
                <div class="flex justify-between items-center mb-6">
                    <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                        Edit Akun
                    </p>
                    <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                        x-on:click="showEditAccountPopUp = false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- POP UP BODY -->
                <form action="{{ route('admin.account.update') }}" method="POST">
                    @method('PUT') @csrf
                    <div class="w-full max-h-96 py-2 overflow-y-auto mb-6 custom-scrollbar">
                        <!-- USER ID -->
                        <input type="hidden" name="user_id" id="edit_user_id" value="{{ old('user_id') }}">
                        
                        <!-- FULL NAME -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Nama Lengkap') }}</p>
                            <input type="text" id="edit_name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                            @error('name', 'edit_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Email') }}</p>
                            <input type="email" id="edit_email" name="email" value="{{ old('email') }}" placeholder="Email"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                            @error('email', 'edit_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ROLE -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Role') }}</p>
                            <input type="text" id="edit_role" name="role" value="{{ old('role') }}"
                                class="border-2 border-primary-20 text-primary-50 bg-blue-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off" readonly>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Kata Sandi Baru') }} <span class="text-primary"><small>opsional</small></span></p>
                            <input type="password" id="edit_password" name="password" placeholder="Kata Sandi Baru"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                            @error('password', 'edit_account')
                                <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PASSWORD CONFIRMATION -->
                        <div class="">
                            <p class="block m-0 mb-2 text-sm font-medium text-gray-900">{{ __('Konfirmasi Kata Sandi Baru') }} <span class="text-primary"><small>opsional</small></span></p>
                            <input type="password" id="edit_password_confirmation" name="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru"
                                class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                                autocomplete="off">
                                @error('password_confirmation', 'edit_account')
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

    <!-- "DELETE ACCOUNT" POP UP -->
    <div class="fixed z-[2220] inset-0"
        x-cloak x-show="showDeleteAccountPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-3/5 lg:1/2 xl:w-2/5 rounded-md p-5">
                <!-- POP UP HEADER -->
                <div class="flex justify-between items-center mb-4">
                    <p class="block m-0 text-lg font-semibold text-gray-900 tracking-wide">
                        Hapus Akun
                    </p>
                    <button type="button" class="block border-none outline-none text-gray-900 hover:text-gray-800 font-medium"
                        x-on:click="showDeleteAccountPopUp = false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- POP UP BODY -->
                <div class="w-full max-h-96 py-2 overflow-y-auto mb-4 custom-scrollbar text-gray-700">
                    <!-- QUESTION TEXT -->
                    <p class="block m-0 mb-4 text-base font-semibold">Anda akan menghapus akun berikut : </p>
                    <div class="grid grid-cols-4 gap-2 mb-4 text-sm">
                        <p class="block m-0">Nama</p>
                        <p class="block m-0 col-span-3 font-semibold" id="delete_account_name"></p>
                        <p class="block m-0">Email</p>
                        <p class="block m-0 col-span-3 font-semibold" id="delete_account_email"></p>
                        <p class="block m-0">Role</p>
                        <p class="block m-0 col-span-3 font-semibold" id="delete_account_role"></p>
                    </div>
                    <p class="block text-base font-semibold">Anda yakin untuk melanjutkan aksi ini?</p>
                </div>
                <!-- SUBMIT BUTTON -->
                <form action="{{ route('admin.account.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="w-full">
                        <button type="submit" name="user_id" id="delete_user_id" class="w-full text-white bg-danger hover:bg-danger-70 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                            {{ __('Hapus') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function validateExtension(file) {
        let inputLabel           = document.getElementById("importAccountInputLabel");
        let preview              = document.getElementById("filePreview");
        let nameField            = document.getElementById("selectedFileName");
        let submitFileBtn        = document.getElementById("submitFileBtn");
        let fileDropZone         = document.getElementById("fileDropZone");
        let dropMessage          = document.getElementById("dropMsg");
        let dropMessageContainer = document.getElementById("dropMsgContainer");

        if (file) {
            let allowedExt      = ['xls', 'xlsx', 'xlsm'];
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
                let fileTarget = document.getElementById("accountFile");
                fileTarget.files = droppedFile;
                validateExtension(fileTarget.files[0]);
            }
        });

        // Validate the file (only validate the extension)
        $("#accountFile").change(function (e) {
            let accountFile = document.getElementById("accountFile");
            validateExtension(accountFile.files[0]);
        });

        // Hide Message Container (if there/if appear) when user click the label
        $("#importAccountInputLabel").click(function () {
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
            let inputFile = document.getElementById("accountFile");
            inputFile.value = "";

            $("#filePreview").removeClass('grid');
            $("#filePreview").addClass('hidden');

            $("#importAccountInputLabel").removeClass('hidden');
            $("#importAccountInputLabel").addClass('block');

            if ($("#fileDropZone").hasClass('dragOver')) {
                $("#fileDropZone").removeClass('dragOver');
                $("#fileDropZone").addClass('border-white');
            }

            $("#submitFileBtn").attr("disabled", true);
            $("#submitFileBtn").removeClass('enabledBtn');
            $("#submitFileBtn").addClass('disabledBtn');
        });

        // Executed when user click pop-up's close button
        $("#closeImportAccountModal").click(function () {
            // Hide File Preview
            if ($("#filePreview").hasClass('grid')) {
                $("#filePreview").removeClass('grid');
                $("#filePreview").addClass('hidden');
            }

            // Show the label
            if ($("#importAccountInputLabel").hasClass('hidden')) {
                $("#importAccountInputLabel").removeClass('hidden');
                $("#importAccountInputLabel").addClass('block');
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

        // edit account
        $(".edit-account-btn").click(function (e) { 
            e.preventDefault();
            let id = $(this).attr("id").split("_")[1];

            let name    = document.getElementById("accountName_"+id).innerText;
            let email   = document.getElementById("accountEmail_"+id).innerText;
            let role    = document.getElementById("accountRole_"+id).innerText;

            $("#edit_user_id").val(id);
            $("#edit_name").val(name);
            $("#edit_email").val(email);
            $("#edit_role").val(role);

        });

        // delete account 
        $(".delete-account-btn").click(function (e) { 
                e.preventDefault();

                let id = $(this).attr("id").split("_")[1];

                let name    = document.getElementById("accountName_"+id).innerText;
                let email   = document.getElementById("accountEmail_"+id).innerText;
                let role    = document.getElementById("accountRole_"+id).innerText;

                $("#delete_user_id").val(id);
                $("#delete_account_name").text(name);
                $("#delete_account_email").text(email);
                $("#delete_account_role").text(role);

            });
    });
</script>
@endsection