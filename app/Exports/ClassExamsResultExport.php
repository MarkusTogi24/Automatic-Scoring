<?php

namespace App\Exports;

use App\Models\Exam;
use App\Models\Classroom;
use App\Models\StudentAndScore;
use App\Models\ClassroomAndMember;
use Maatwebsite\Excel\Concerns\FromArray;

class ClassExamsResultExport implements FromArray
{
    protected $class_obj;

    public function __construct(Classroom $class)
    {
        $this->class_obj = $class;
    }

    public function array(): array
    {
        $classroom = $this->class_obj;
        $exams = Exam::query()
            ->where('class_id', $classroom->id)
            ->orderBy("exam.start_time", "asc")
            ->get();

        $classExamsResult = ClassroomAndMember::select(
                "classroom_and_member.classroom_id as classroom",
                "users.id as member_id",
                "users.name as member_name",
            )
            ->leftJoin("users", function($join) {
                $join->on("classroom_and_member.member_id", "=", "users.id")->where("role", "SISWA");
            })
            ->where("classroom_id", $classroom->id)
            ->whereNotNull("users.name")
            ->get();

        $classExamsResult->map(function ($result) use ($exams, $classroom) {
            $scores = array();
            $total_score = 0;
            foreach ($exams as $exam) {
                $exam_score = StudentAndScore::select("student_and_score.total_score as score")
                    ->where('exam_id', $exam->id)
                    ->where('student_id', $result->member_id)
                    ->first();
                if($exam_score !== null){
                    $total_score += $exam_score->score;
                }
                $scores[] = $exam_score;
            }
            $result->exams = $scores;
            $result->total = $total_score;
        });

        $class_exam_result_arr = array();
        $class_exam_result_header = ["Nomor", "Nama Siswa"];

        foreach ($exams as $exam) {
            $class_exam_result_header[] = $exam->name;
        }
        $class_exam_result_header[] = "Total Skor";

        $class_exam_result_arr[] = $class_exam_result_header;

        foreach ($classExamsResult as $index => $result) {
            $class_exam_result_row = array();
            $class_exam_result_row[] = strval($index+1);
            $class_exam_result_row[] = $result->member_name;

            if($result->exams === null){
                for ($i=0; $i<$exams->count(); $i++) {
                    $class_exam_result_row[] = strval("0.0");
                }
            }else{
                foreach($result->exams as $exam){
                    if($exam === null){
                        $class_exam_result_row[] = strval("0.0");
                    }else{
                        $class_exam_result_row[] = strval($exam->score);
                    }
                }
            }
            $class_exam_result_row[] = strval($result->total);
            $class_exam_result_arr[] = $class_exam_result_row;
        }

        return $class_exam_result_arr;
    }
}
