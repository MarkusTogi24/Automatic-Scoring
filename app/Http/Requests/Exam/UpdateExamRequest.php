<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    protected $errorBag = 'edit_exam';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'name'                  => 'required',
            'start_time'            => 'required|date|after:now',
            'duration'              => 'required',
            'description'           => 'required',
            'is_open'               => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => 'Judul ujian',
            'start_time'            => 'Waktu mulai',
            'duration'              => 'Durasi',
            'description'           => 'Deskripsi ujian',
            'is_open'               => 'Status'
        ];
    }

    public function messages()
    {
        return [
            'start_time.after'      => 'Waktu mulai yang dipilih sudah berlalu.'
        ];
    }
}
