<?php

namespace App\Http\Requests\Admin\Account;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    protected $errorBag = 'edit_account';

    public function authorize()
    {
        return auth()->user()->role === "ADMIN";
    }

    public function rules()
    {
        return [
            'name'                  =>  'required',
            'email'                 => [
                'required',
                Rule::unique('users')->ignore(request()->get('user_id'), 'id')
            ],
            'role'                  =>  'required',
            'password'              =>  [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
            'password_confirmation' =>  'nullable',
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

    // public function messages()
    // {
    //     return [
    //         'role.required'         => 'Anda wajib memilih salah satu dari role tersedia.'
    //     ];
    // }
}
