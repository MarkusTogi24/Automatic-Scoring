<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\StudentAndScore;
use App\Models\ClassroomAndMember;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassExamsResultExport;

class StudentAndScoreController extends Controller
{
    public function index()
    {
        //
    }
    
    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        //
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        // exportClassExamsResult
    }

    public function exportClassExamsResult(Classroom $classroom){
        return Excel::download(
            new ClassExamsResultExport($classroom), 
            "Rekap Hasil Seluruh Ujian Kelas {$classroom->name}.xlsx"
        );
    }
}
