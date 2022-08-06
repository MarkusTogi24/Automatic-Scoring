<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    protected $errorBag = 'edit_question';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'question_id'           =>  'required',
            'question'              =>  'required',
            'answer_key'            =>  'required',
            'max_score'             =>  'required',
        ];
    }

    public function attributes()
    {
        return [
            'question'              =>  'Pertanyaan',
            'answer_key'            =>  'Jawaban',
            'max_score'             =>  'Bobot soal',
        ];
    }
}
