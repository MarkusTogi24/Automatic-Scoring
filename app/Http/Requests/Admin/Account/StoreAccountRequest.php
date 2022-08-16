<?php

namespace App\Http\Requests\Admin\Account;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    protected $errorBag = 'create_account';

    public function authorize()
    {
        return auth()->user()->role === "ADMIN";
    }

    public function rules()
    {
        return [
            'name'                  =>  'required',
            'email'                 =>  'required|unique:users',
            'role'                  =>  'required',
            'password'              =>  [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
            'password_confirmation' =>  'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  =>  'Nama lengkap',
            'email'                 =>  'Email',
            'role'                  =>  'Role',
            'password'              =>  'Kata sandi',
            'password_confirmation' =>  'Konfirmasi kata sandi',
        ];
    }

    public function messages()
    {
        return [
            'role.required'         => 'Anda wajib memilih salah satu dari role tersedia.'
        ];
    }
    
}
