<?php

namespace App\Action;

use App\Models\Exam;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuestionsStoreAction
{
    public function importQuestions(Collection $questions, Exam $exam): int|string
    {
        $errorMessage = "Skor maksimal pada soal nomor ";
        $errorCount = 0;
        $savedQuestion = 0;

        foreach ($questions[0] as $index => $question) {
            if($index == 0) continue;
            if (!is_numeric($question[3])) {
                $errorMessage .= $question[0] . ", ";
                $errorCount++;
            }
        }
        if($errorCount>0){
            $errorMessage .= "tidak valid, harap periksa file sebelum diunggah.";
            return $errorMessage;
        }else{
            foreach ($questions[0] as $index => $question) {
                if($index == 0) continue;

                $new_question               = new Question;
                $new_question->exam_id      = $exam->id;
                $new_question->question     = $question[1];
                $new_question->answer_key   = $question[2];
                $new_question->max_score    = (float) $question[3];
                $new_question->save();

                $savedQuestion++;
            }
            return $savedQuestion;
        }

        dd($savedQuestion, $errorCount);

        return $savedQuestion;
    }
    
}
