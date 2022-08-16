<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Account\StoreAccountRequest;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::paginate(10);

        return view('pages.admin.account.index', compact('accounts'));
    }

    public function store(StoreAccountRequest $request)
    {
        $validated = $request->validated();

        $new_account            = new User;
        $new_account->name      = $validated["name"];
        $new_account->email     = $validated["email"];
        $new_account->role      = $validated["role"];
        $new_account->password  = Hash::make($validated["password"]);
        $new_account->save();

        return redirect()->route('admin.account.index')
            ->with("success","Akun baru berhasil dibuat!");
    }

    public function upload(Request $request)
    {
        $users_email = User::all()->pluck('email')->toArray();

        $file = $request->file('accountFile');

        $emailExistMessage = "Email ";
        $emailExistInDb = 0;
        $input_email = array();

        $csv_file = fopen($file, 'r');
        $row_index = 0;
        while (($row_data = fgetcsv($csv_file)) != false) {
            if ($row_index != 0){
                $input_email[] = $row_data[1];
                if (in_array( $row_data[1], $users_email )){
                    $emailExistMessage .= $row_data[1] . ", ";
                    $emailExistInDb++;
                }
            }
            $row_index += 1;
        }
        fclose($csv_file);

        $emailExistMessage .= "sudah digunakan di sistem, harap periksa file .csv yang akan diunggah.";
        
        if($emailExistInDb > 0){
            return back()->with("failed",$emailExistMessage);
        }

        $emailDuplicatesMessage = "Terdapat lebih dari 1 data dengan email ";

        $duplicates = array();
        foreach(array_count_values($input_email) as $val => $count){
            if($count > 1){
                $duplicates[] = $val;
                $emailDuplicatesMessage .= $val . ", ";
            } 
        }

        $emailDuplicatesMessage .= "harap periksa file .csv yang akan diunggah.";

        if(sizeof($duplicates) > 0){
            return back()->with("failed",$emailDuplicatesMessage);
        }

        dd($input_email, $emailExistInDb, $emailExistMessage, $emailDuplicatesMessage, $duplicates);
        
        $csv_file = fopen($file, 'r');
        $row_index = 0;
        $data_count = 0;
        while (($row_data = fgetcsv($csv_file)) != false) {
            if ($row_index != 0){

                $new_account                = new User;
                $new_account->name          = $row_data[0];
                $new_account->email         = $row_data[1];
                $new_account->role          = $row_data[2];
                $new_account->password      = Hash::make($row_data[3]);
                $new_account->save();

                $data_count++;
            }
            $row_index += 1;
        }
        fclose($csv_file);

        return back()->with("success","{$data_count} akun baru berhasil dibuat!");
    }
}
