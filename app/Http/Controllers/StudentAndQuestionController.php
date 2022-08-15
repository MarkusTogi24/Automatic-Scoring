<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\StudentAndScore;
use App\Http\Helpers\ExamHelper;
use App\Models\StudentAndQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class StudentAndQuestionController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new ExamHelper;
    }
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
        $updated_exam_info = Exam::find($exam->id);

        $entity = new StudentAndQuestion;

        $entity->question_id    = $request->question_id;
        $entity->answer         = $request->answer;
        $entity->student_id     = Auth::user()->id;
        $entity->score          = 0;
        $entity->save();
        
        if ($updated_exam_info->is_open == 0){
            $this->helper->save_total_score($exam->id);
            $data = [
                "exam_status"   => "closed",
                "classroom_id"  => $exam->class_id,
                "exam_id"       => $exam->id
            ];
            return Response::json($data);
        } else {
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
        $updated_exam_info = Exam::find($exam->id);
        
        $entity = StudentAndQuestion::firstWhere('id', $request->id);

        $entity->question_id    = $request->question_id;
        $entity->answer         = $request->answer;
        $entity->student_id     = Auth::user()->id;
        $entity->score          = 0;
        $entity->save();
        
        if ($updated_exam_info->is_open == 0){
            $this->helper->save_total_score($exam->id);
            $data = [
                "exam_status"   => "closed",
                "classroom_id"  => $exam->class_id,
                "exam_id"       => $exam->id
            ];
            return Response::json($data);
        }else {
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
    }
    
    public function destroy($id)
    {
        //
    }

    public function submit(Exam $exam) {
        $this->helper->save_total_score($exam->id);
    }
}
