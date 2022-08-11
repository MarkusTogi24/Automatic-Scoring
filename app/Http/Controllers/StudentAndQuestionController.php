<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\StudentAndQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class StudentAndQuestionController extends Controller
{
    public function index()
    {
        //
    }
    
    public function create()
    {
        //
    }
    
    public function store(Request $request, Exam $exam)
    {
        $entity = new StudentAndQuestion;

        $entity->question_id    = $request->question_id;
        $entity->answer         = $request->answer;
        $entity->student_id     = Auth::user()->id;
        $entity->score          = 0;
        $entity->save();

        $questions = Question::select("question.*", "student_and_question.answer as answer", "student_and_question.id as answer_id")
            ->leftJoin('student_and_question', function ($join) {
                $join->on('question.id', 'student_and_question.question_id');
                $join->on('student_and_question.student_id', DB::raw(Auth::user()->id));
            })                   
            ->where('question.exam_id', $exam->id)
            ->orderBy('id')
            ->get();

        return Response::json($questions);
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, Exam $exam)
    {
        $entity = StudentAndQuestion::firstWhere('id', $request->id);

        $entity->question_id    = $request->question_id;
        $entity->answer         = $request->answer;
        $entity->student_id     = Auth::user()->id;
        $entity->score          = 0;
        $entity->save();

        $questions = Question::select("question.*", "student_and_question.answer as answer", "student_and_question.id as answer_id")
            ->leftJoin('student_and_question', function ($join) {
                $join->on('question.id', 'student_and_question.question_id');
                $join->on('student_and_question.student_id', DB::raw(Auth::user()->id));
            })                   
            ->where('question.exam_id', $exam->id)
            ->orderBy('id')
            ->get();

        return Response::json($questions);
        
        // return compact('answer_id', 'answer');
    }
    
    public function destroy($id)
    {
        //
    }

    public function saveAnswer($exam_id, $question_id, $answer){
        $entity = new StudentAndQuestion;

        $entity->question_id = $question_id;
        $entity->answer = $answer;
        $entity->student_id = Auth::user()->id;
        $entity->score = 1;
        $entity->save();

        $answer_id = $entity->id;

        $total_score = StudentAndQuestion::select("SUM(score)")->where('exam_id', $exam_id)->get();
        
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
