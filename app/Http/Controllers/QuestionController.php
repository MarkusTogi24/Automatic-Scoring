<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ExamHelper;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    private $helper;

    public function __construct()
    {
        $this->helper = new ExamHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($classroom_id, $exam_id)
    {
        //
        $this->helper->authorizing_classroom_member($classroom_id);

        $questions = Question::all()->where('exam_id', $exam_id);

        return $questions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($classroom_id, $exam_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        return view ('add_question', compact('classroom_id', 'exam_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($classroom_id, $exam_id, Request $request)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $question = new Question;
        $question->exam_id = $exam_id;
        $question->question = $request->question;
        $question->answer_key = $request->answer_key;
        $question->max_score = $request->max_score;
        $question->save();

        return $question;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($classroom_id, $exam_id, $question_id)
    {
        //
        $exam = Exam::find($exam_id);
        $this->helper->authorizing_exam_question_access($classroom_id, $exam);

        $question = Question::find($question_id);

        return $question;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($classroom_id, $question_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);
        
        $question = Question::find($question_id);
        return view ('update_question', compact('classroom_id', 'question_id', 'question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $classroom_id, $question_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);
        
        $question = Question::find($question_id);
        $question->question = $request->question;
        $question->answer_key = $request->answer_key;
        $question->max_score = $request->max_score;
        $question->save();

        return $question;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($classroom_id, $question_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);
        
        $question = Question::find($question_id);
        $question->delete();
    }
}
