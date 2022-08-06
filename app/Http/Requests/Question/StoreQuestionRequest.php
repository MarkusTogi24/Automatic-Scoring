<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    protected $errorBag = 'create_question';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
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
