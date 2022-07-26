<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ClassroomHelper;
use App\Models\Classroom;
use App\Models\ClassroomAndMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassroomAndMemberController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->middleware("auth");
        $this->helper = new ClassroomHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($classroom_id)
    {
        //
        $this->helper->authorizing_classroom_member($classroom_id);

        $members = DB::select(DB::raw("SELECT users.name, users.email, users.role FROM classroom_and_member LEFT JOIN users ON classroom_and_member.member_id = users.id WHERE classroom_id = $classroom_id;"));
        return $members;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('member_create');
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
        $this->helper->authorizing_by_role(["GURU", "SISWA"]);
        
        $classroom = Classroom::all()->where("enrollment_key", $request->enrollment_key)->first();
        echo $classroom;
        if ($classroom != null){
            if (count(ClassroomAndMember::all()->where('classroom_id', $classroom->id)->where('member_id', Auth::user()->id)) == 0){
                $member = new ClassroomAndMember;
                $member->classroom_id = $classroom->id;
                $member->member_id = Auth::user()->id;
    
                $member->save();    
            }
        }
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
    }
}
