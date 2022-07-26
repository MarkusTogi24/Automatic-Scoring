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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->helper->authorizing_by_role(["GURU", "SISWA"]);
        
        $classrooms = DB::select(DB::raw("SELECT * FROM classroom LEFT JOIN classroom_and_member ON classroom.id = classroom_and_member.classroom_id WHERE classroom_and_member.member_id = ".Auth::user()->id.";"));

        return $classrooms;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->helper->authorizing_by_role("GURU");

        return view('create_classroom');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom = Classroom::find($id);
        
        return $classroom;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom = Classroom::find($id);

        return view('update_classroom', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $classroom = Classroom::find($id);
        
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom->name = $request->name;
        $classroom->description = $request->description;

        $classroom->save();

        return $classroom;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $classroom = Classroom::find($id);
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($id);

        $classroom->delete();
    }
}
