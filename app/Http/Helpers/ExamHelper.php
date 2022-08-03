<?php

namespace App\Http\Helpers;

use Carbon\Carbon;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;

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
}

?>