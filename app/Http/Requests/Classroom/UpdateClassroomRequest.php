<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
{
    protected $errorBag = 'edit_classroom';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'name'                  =>  'required',
            'description'           =>  'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  =>  'Nama kelas',
            'description'           =>  'Deskripsi kelas',
        ];
    }
}
