<?php

namespace App\Http\Requests\Exam;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    protected $errorBag = 'create_exam';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'name'                  =>  'required',
            'start_time'            =>  'required|date|after:now',
            'duration'              =>  'required',
            'description'           =>  'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  =>  'Judul ujian',
            'start_time'            =>  'Waktu mulai',
            'duration'              =>  'Durasi',
            'description'           =>  'Deskripsi ujian',
        ];
    }

    public function messages()
    {
        return [
            'start_time.after'      => 'Waktu mulai yang dipilih sudah berlalu.'
        ];
    }
}
