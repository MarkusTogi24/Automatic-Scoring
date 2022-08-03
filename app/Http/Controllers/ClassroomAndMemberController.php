<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ClassroomHelper;
use App\Models\Classroom;
use App\Models\ClassroomAndMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomAndMemberController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->middleware("auth");
        $this->helper = new ClassroomHelper;
    }
    
    public function index($classroom_id)
    {
        //
        $this->helper->authorizing_classroom_member($classroom_id);

        $members = ClassroomAndMember::all()->where('classroom_id', $classroom_id);
        return $members;
    }
    
    public function create()
    {
        //
        return view('member_create');
    }
    
    public function store(Request $request)
    {
        $this->helper->authorizing_by_role(["GURU", "SISWA"]);
        
        $classroom = Classroom::firstWhere("enrollment_key", $request->enrollment_key);

        if(!$classroom){
            return back()
                ->with('failed', "Kode kelas yang anda masukkan tidak dikenali, harap periksa kembali dan coba lagi!" );
        }

        if(ClassroomAndMember::where('classroom_id', $classroom->id)->where('member_id', Auth::user()->id)->count() > 0){
            return back()
                ->with('warning', "Anda telah bergabung di kelas {$classroom->name}!" );
        }

        ClassroomAndMember::create([
            'classroom_id'  => $classroom->id,
            'member_id'     => Auth::user()->id
        ]);

        return redirect()->route('classroom.index')
            ->with('success', "Sekarang anda bergabung di kelas {$classroom->name}!");
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
        //
    }
}
