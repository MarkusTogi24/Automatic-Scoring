<?php

namespace App\Action;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Collection;

class QuestionsStoreAction
{
    public function importQuestions(Collection $questions, Exam $exam): int|string
    {
        $errorMessage   = "Skor maksimal pada soal nomor ";
        $errorCount     = 0;
        $savedQuestion  = 0;
        $emptyCellCount = 0;

        foreach ($questions[0] as $index => $question) {
            if($index == 0) continue;
            
            // CHECK IF THERE'S AN EMPTY CELL IN A ROW
            if( $question[1] == null || $question[2] == null || $question[3] == null ){
                $emptyCellCount++;
            }
            // CHECK IF THERE'S A NON-NUMERIC MAX_SCORE
            if (!is_numeric($question[3])) {
                $errorMessage .= $question[0] . ", ";
                $errorCount++;
            }
        }
        
        if($emptyCellCount > 0){
            return "Masih terdapat sel data yang kosong, harap periksa fail sebelum diunggah.";
        }else if($errorCount>0){
            $errorMessage .= "tidak valid, harap periksa fail sebelum diunggah.";
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
    }
}
