<?php

namespace App\Action;

use App\Models\User;
use App\Models\Question;
use App\Models\StudentAndScore;
use App\Models\ClassroomAndMember;
use App\Models\Exam;
use App\Models\StudentAndQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class ExamResultExportAction
{
    public function collectData(Exam $exam)
    {
        $exam_result = ClassroomAndMember::select(
                "classroom_and_member.classroom_id as classroom",
                "users.id as member_id",
                "users.name as member_name",
            )
            ->leftJoin("users", function($join) {
                $join->on("classroom_and_member.member_id", "=", "users.id")->where("role", "SISWA");
            })
            ->where("classroom_id", $exam->class_id)
            ->whereNotNull("users.name")
            ->get();

        $questions = Question::where('exam_id', $exam->id)->get('id');

        // $exam_result_arr = array();

        $exam_result->map(function ($result) use ($questions, $exam) {
            $scores = array();
            foreach ($questions as $question) {
                $scores[] = StudentAndQuestion::select("student_and_question.score as score")
                    ->where('question_id', $question->id)
                    ->where('student_id', $result->member_id)
                    ->first();
            }
            $result->questions = $scores;
            $result->total_score = StudentAndScore::select("student_and_score.total_score as total_score")
                ->where('exam_id', $exam->id)
                ->where('student_id', $result->member_id)
                ->first();
        });
        
    }
    
}
