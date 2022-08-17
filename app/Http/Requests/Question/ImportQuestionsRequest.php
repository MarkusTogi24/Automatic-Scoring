<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class ImportQuestionsRequest extends FormRequest
{
    protected $errorBag = 'import_questions';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        $extensions = ['xls', 'xlsx', 'xlsm', 'csv'];

        return [
            'questionFile' => [
                'required', 
                'file', 
                function ($attribute, $value, $fail) use ($extensions) {
                    if (!in_array($value->getClientOriginalExtension(), $extensions)) {
                        $fail('File unggahan harus berekstensi xls, xlsx, atau xlsm.');
                    }
                }
            ],
        ];
    }

    public function attributes()
    {
        return [
            'questionFile'              =>  'File unggahan',
        ];
    }
}
