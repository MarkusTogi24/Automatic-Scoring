<?php

namespace App\Http\Helpers;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\StudentAndQuestion;
use App\Models\StudentAndScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamHelper extends ClassroomHelper{

    function authorizing_exam_question_access_for_student($classroom_id, $exam_id){
        $exam = Exam::find($exam_id);
        $this->authorizing_classroom_member($classroom_id);
        if ($exam->is_open == 0){
            abort(403);
        }
    }

    function get_duration(Exam $exam){

        $start_time = new Carbon($exam->start_time, 'Asia/Jakarta');
        $end_time   = new Carbon($exam->end_time,   'Asia/Jakarta');
        $difference = $start_time->diffInMinutes($end_time);

        $hours      = intval(($difference)/60);
        $minutes    = $difference%60;

        $duration = array(
            "human_format" => "",
            "basic_format" => ""
        );

        // Human Format
        ($hours > 0)    && $duration["human_format"] .= strval($hours)   . " jam ";
        ($minutes > 0)  && $duration["human_format"] .= strval($minutes) . " menit";

        // Basic Format
        if($hours > 0){
            $hours > 9 ? 
                $duration["basic_format"] .= strval($hours) . ":" : 
                $duration["basic_format"] .= "0".strval($hours).":" ;
        }else{
            $duration["basic_format"] .= "00:" ;
        }
        
        $minutes > 9 ? 
            $duration["basic_format"] .= strval($minutes)     : 
            $duration["basic_format"] .= "0".strval($minutes) ;

        return $duration;
    }

    function save_total_score($exam_id){
        $total_score_arr = StudentAndQuestion::select(DB::raw("sum(student_and_question.score) as score"))
            ->leftJoin("question", "question.id", '=', "student_and_question.question_id")
            ->where("question.exam_id", $exam_id)
            ->where("student_and_question.student_id", Auth::user()->id)
            ->get();

        $total_score_entity = new StudentAndScore;
        $total_score_entity->exam_id = $exam_id;
        $total_score_entity->student_id = Auth::user()->id;
        $total_score_entity->total_score = $total_score_arr[0]->score;
        $total_score_entity->save();
    }
}

?>