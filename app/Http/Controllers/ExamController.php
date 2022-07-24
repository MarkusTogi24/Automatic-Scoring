<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ClassroomHelper;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{

    private $helper;

    public function __construct()
    {
        $this->helper = new ClassroomHelper;
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        
        return view('create_ujian', compact('id'));
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
        $this->helper->authorizing_classroom_member($request->class_id);

        $exam = new Exam;
        $exam->class_id = $request->class_id;
        $exam->name = $request->name;
        $exam->description = $request->description;
        $exam->start_time = $request->start_time;
        $exam->end_time = $request->end_time;
        $exam->is_open = $request->is_open;
        $exam->save();

        return $exam;
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
        $this->helper->authorizing_classroom_member($id);
        $exams = Exam::all()->where('class_id', $id);

        return $exams;
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
        $exam = Exam::find($id);
        return view ('update_ujian', compact('exam'));
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
        $exam = Exam::find($id);

        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($exam->class_id);
        
        $exam->name = $request->name;
        $exam->description = $request->description;
        $exam->start_time = $request->start_time;
        $exam->end_time = $request->end_time;
        $exam->is_open = $request->is_open;
        $exam->save();

        return $exam;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($classroom_id, $exam_id)
    {
        //
        $exam = Exam::find($exam_id);

        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $exam->delete();
    }
}
