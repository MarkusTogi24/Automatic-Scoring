<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\ClassroomAndMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\ClassroomHelper;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;

class ClassroomController extends Controller
{

    public $helper;

    public function __construct()
    {
        $this->middleware("auth");
        $this->helper = new ClassroomHelper();
    }
    
    public function index()
    {
        $this->helper->authorizing_by_role(["GURU", "SISWA"]);
        
        $classrooms = DB::select(DB::raw("SELECT * FROM classroom LEFT JOIN classroom_and_member ON classroom.id = classroom_and_member.classroom_id WHERE classroom_and_member.member_id = ".Auth::user()->id.";"));
        // $classrooms = Classroom::query()
        //     ->leftJoin('classroom_and_member', 'classroom.id', '=', 'classroom_and_member.classroom_id')
        //     ->where('classroom_and_member.member_id', Auth::user()->id)
        //     ->get();

        // dd($classrooms);
        return view('pages.user.classroom.index', compact('classrooms'));
    }
    
    public function create()
    {
        $this->helper->authorizing_by_role("GURU");

        return view('create_classroom');
    }
    
    public function store(StoreClassroomRequest $request)
    {
        $this->helper->authorizing_by_role("GURU");

        $validated = $request->validated();
        
        $new_classroom = new Classroom;
        $new_classroom->teacher_id      = Auth::user()->id;
        $new_classroom->name            = $validated['name'];
        $new_classroom->description     = $validated['description'];
        $new_classroom->enrollment_key  = $this->helper->generate_enrollment_key();
        $new_classroom->save();

        $new_classroom_and_member = new ClassroomAndMember;
        $new_classroom_and_member->classroom_id = $new_classroom->id;
        $new_classroom_and_member->member_id    = Auth::user()->id;
        $new_classroom_and_member->save();

        return redirect()->route('classroom.index')
            ->with("success","Kelas {$new_classroom->name} berhasil dibuat!");
    }
    
    public function show($id)
    {
        $this->helper->authorizing_by_role(["GURU", "SISWA"]);
        $this->helper->authorizing_classroom_member($id);

        $classroom = Classroom::find($id);

        $exams = DB::select(DB::raw("SELECT * FROM exam WHERE class_id = ".$id." ORDER BY start_time DESC;"));

        // $exams = Exam::query()
        //     ->where('class_id', $id)
        //     ->orderBy('start_time', 'desc')
        //     ->get();

        // dd($classroom, $exams);
        
        return view('pages.user.classroom.show', compact('classroom', 'exams'));
    }
    
    public function edit($id)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom = Classroom::find($id);

        return view('update_classroom', compact('classroom'));
    }
    
    public function update(UpdateClassroomRequest $request, $id)
    {
        $classroom = Classroom::find($id);
        
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $validated = $request->validated();

        $classroom->name        = $validated['name'];
        $classroom->description = $validated['description'];

        $classroom->save();

        return redirect()->route('classroom.show', $id)
            ->with( "success", "Perubahan pada kelas {$classroom->name} berhasil disimpan!");
    }
    
    public function destroy($id)
    {
        $classroom = Classroom::find($id);
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom->delete();
    }

}
