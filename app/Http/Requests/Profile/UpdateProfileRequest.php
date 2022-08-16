<?php

namespace App\Http\Requests\Profile;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'name'                  => 'required',
            'email'                 => [
                'required',
                Rule::unique('users')->ignore($this->user->id, 'id')
            ],
            'old_password'          => 'required|current_password',
            'new_password'          => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
            'new_password_confirmation' => 'nullable'
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => 'Nama lengkap',
            'email'                 => 'Alamat email',
            'old_password'          => 'Kata sandi lama',
            'new_password'          => 'Kata sandi baru',
            'password_confirmation' => 'Konfirmasi kata sandi baru'
        ];
    }

    public function messages()
    {
        return [
            'old_password.current_password' => 'Kata sandi lama yang anda masukkan salah.'
        ];
    }
}
