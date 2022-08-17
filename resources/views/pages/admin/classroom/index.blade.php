@extends('layouts.master')

@section('content')

<div class="w-full">
    @include('components.session-alert')

    <p class="block m-0 mb-4 text-lg xl:text-xl font-bold text-primary tracking-wider w-full">
        Data Kelas
    </p>

    <!-- CLASSROOM TABLE -->
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-4">
        <table class="w-full text-sm text-left text-primary-50">
            <thead class="text-xs text-primary-70 uppercase bg-primary-10">
                <tr>
                    <th scope="col" class="py-3 px-6 text-center"> # </th>
                    <th scope="col" class="py-3 px-6"> Nama Kelas</th>
                    <th scope="col" class="py-3 px-6"> Deskripsi Singkat </th>
                    <th scope="col" class="py-3 px-6"> Nama Guru </th>
                    <th scope="col" class="py-3 px-6 text-center"> Kode Kelas </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classrooms as $classroom)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 text-center">
                            {{($classrooms->currentPage() - 1) * $classrooms->perPage() + $loop->iteration}}
                        </td>
                        <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap">
                            {{ $classroom->name }}
                        </th>
                        <td class="py-4 px-6 whitespace-nowrap">
                            {{ $classroom->description }}
                        </td>
                        @php
                            $exploded_teacher_name = explode(" ",$classroom->teacher);
                        @endphp
                        <td class="py-4 px-6 whitespace-nowrap">
                            {{ implode(" ", array_splice($exploded_teacher_name, 0, 2)) }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{ $classroom->enrollment_key }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $classrooms->onEachSide(2)->links() }}
</div>
@endsection

@section('scripts')
@endsection