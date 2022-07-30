<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ClassroomHelper;
use App\Models\Classroom;
use App\Models\ClassroomAndMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // dd($classrooms);
        return view('pages.user.classroom.index', compact('classrooms'));
    }
    
    public function create()
    {
        $this->helper->authorizing_by_role("GURU");

        return view('create_classroom');
    }
    
    public function store(Request $request)
    {
        $this->helper->authorizing_by_role("GURU");
        
        $new_classroom = new Classroom;
        $new_classroom->teacher_id = Auth::user()->id;
        $new_classroom->name = $request->name;
        $new_classroom->description = $request->description;
        $new_classroom->enrollment_key = $this->helper->generate_enrollment_key();
        $new_classroom->save();

        $new_classroom_and_member = new ClassroomAndMember;
        $new_classroom_and_member->classroom_id = $new_classroom->id;
        $new_classroom_and_member->member_id = Auth::user()->id;
        $new_classroom_and_member->save();

        return back()->with(
            'alert',[
                "message"   => "Kelas {$new_classroom->name} berhasil dibuat!",
                "status"    => "success"
            ]);
    }
    
    public function show($id)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom = Classroom::find($id);
        
        return $classroom;
    }
    
    public function edit($id)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom = Classroom::find($id);

        return view('update_classroom', compact('classroom'));
    }
    
    public function update(Request $request, $id)
    {
        $classroom = Classroom::find($id);
        
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom->name = $request->name;
        $classroom->description = $request->description;

        $classroom->save();

        return $classroom;
    }
    
    public function destroy($id)
    {
        $classroom = Classroom::find($id);
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom->delete();
    }

    public function enroll(Request $request)
    {
        $classroom = Classroom::firstWhere('enrollment_key', $request->enrollment_key);

        if(!$classroom){
            return back()->with(
                'alert',[
                    "message"   => "Kode kelas yang anda masukkan tidak dikenali, harap periksa kembali dan coba lagi!",
                    "status"    => "failed"
                ]);
        }

        ClassroomAndMember::create([
            'classroom_id'  => $classroom->id,
            'member_id'     => Auth::user()->id
        ]);

        return back()->with(
            'alert',[
                "message"   => "Anda telah berhasil bergabung di kelas {$classroom->name}!",
                "status"    => "success"
            ]);
    }
}
