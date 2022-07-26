<?php

namespace App\Http\Helpers;

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
}

?>