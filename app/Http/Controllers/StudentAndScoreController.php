<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class StudentAndScoreController extends Controller
{
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
    public function create()
    {
        //
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

    public function showRecordedScoreAllExam($class_id){
        $exam_all_and_score = Exam
        ::select("exam.id as exam_id", "exam.name as exam_name", "student_and_score.total_score as total_score", "users.name as name")
        ->leftJoin("student_and_score", "exam.id", "=", "student_and_score.exam_id")
        ->leftJoin("users", "student_and_score.student_id", "=", "users.id")
        ->where("exam.class_id", $class_id)
        ->whereNotNull("student_and_score.total_score")
        ->get();

        return compact('exam_all_and_score');
    }

    public function showRecordedScorePerExam($exam_id){
        $exam_score = Exam
        ::select("exam.id as exam_id", "exam.name as exam_name", "student_and_score.total_score as total_score", "users.name as name")
        ->leftJoin("student_and_score", "exam.id", "=", "student_and_score.exam_id")
        ->leftJoin("users", "student_and_score.student_id", "=", "users.id")
        ->where("exam.id", $exam_id)
        ->whereNotNull("student_and_score.total_score")
        ->get();

        return compact('exam_score');
    }
}
