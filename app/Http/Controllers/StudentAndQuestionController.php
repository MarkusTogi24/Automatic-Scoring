<?php

namespace App\Http\Controllers;

use App\Models\StudentAndQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAndQuestionController extends Controller
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

    public function saveAnswer($question_id, $answer){
        $entity = new StudentAndQuestion;

        $entity->question_id = $question_id;
        $entity->answer = $answer;
        $entity->student_id = Auth::user()->id;
        $entity->score = 1;
        $entity->save();

        $answer_id = $entity->id;
        
        return compact('answer_id', 'answer');
    }
    
    public function updateAnswer($answer_id, $answer){
        $entity = StudentAndQuestion::find($answer_id);

        $entity->answer = $answer;
        $entity->score = 1;
        $entity->save();

        return compact('answer_id', 'answer');
    }
}
