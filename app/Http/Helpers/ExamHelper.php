<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;

class ExamHelper extends ClassroomHelper{

    function authorizing_exam_question_access($classroom_id, $exam){
        $this->authorizing_classroom_member($classroom_id);
        if (Auth::user()->role == "SISWA"){
            if ($exam->is_open == 0){
                abort(403);
            }
        }
    }
}

?>