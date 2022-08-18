<?php

namespace App\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;

class ImportAccountsRequest extends FormRequest
{
    protected $errorBag = 'import_accounts';

    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        $extensions = ['xls', 'xlsx', 'xlsm'];

        return [
            'accountFile' => [
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
            'accountFile'              =>  'File unggahan',
        ];
    }
}
