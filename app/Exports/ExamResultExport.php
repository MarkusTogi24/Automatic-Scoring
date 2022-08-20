<?php

namespace App\Exports;

use App\Models\Exam;
use App\Models\Question;
use App\Models\StudentAndScore;
use App\Models\ClassroomAndMember;
use App\Models\StudentAndQuestion;
use Maatwebsite\Excel\Concerns\FromArray;

class ExamResultExport implements FromArray
{
    protected $exam_obj;

    public function __construct(Exam $exam)
    {
        $this->exam_obj = $exam;
    }

    public function array(): array
    {
        $exam = $this->exam_obj;
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

        $exam_result->map(function ($result) use ($questions, $exam) {
            $scores = array();
            foreach ($questions as $question) {
                $scores[] = StudentAndQuestion::select("student_and_question.score as score")
                    ->where('question_id', $question->id)
                    ->where('student_id', $result->member_id)
                    ->first();
            }
            $result->questions = $scores;
            $result->total = StudentAndScore::select("student_and_score.total_score as score")
                ->where('exam_id', $exam->id)
                ->where('student_id', $result->member_id)
                ->first();
        });

        $exam_result_arr = array();
        $exam_result_header = ["Nomor", "Nama Lengkap"];

        foreach ($questions as $index => $value) {
            $exam_result_header[] = "Soal " . strval($index+1);
        }
        $exam_result_header[] = "Total Skor";

        $exam_result_arr[] = $exam_result_header;

        foreach ($exam_result as $index => $result) {
            $exam_result_row = array();
            $exam_result_row[] = strval($index+1);
            $exam_result_row[] = $result->member_name;

            if($result->questions === null){
                for ($i=0; $i<$questions->count(); $i++) {
                    $exam_result_row[] = strval("0.0");
                }
            }else{
                foreach($result->questions as $question){
                    if($question === null){
                        $exam_result_row[] = strval("0.0");
                    }else{
                        $exam_result_row[] = strval($question->score);
                    }
                }
            }
            if($result->total === null){
                $exam_result_row[] = strval("0.0");
            }else{
                $exam_result_row[] = strval($result->total->score);
            }
            $exam_result_arr[] = $exam_result_row;
        }

        return $exam_result_arr;
    }
}
