<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\AccountsImport;
use App\Action\AccountsStoreAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Account\StoreAccountRequest;
use App\Http\Requests\Admin\Account\UpdateAccountRequest;
use App\Http\Requests\Admin\Account\ImportAccountsRequest;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::query()
            ->where('id', '!=', Auth::user()->id)
            ->where('role', '!=', 'ADMIN')
            ->orderBy('role')
            ->paginate(10);

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

    public function update(UpdateAccountRequest $request)
    {
        $validated = $request->validated();
        
        $account            = User::find($request->user_id);

        $account->name      = $validated["name"];
        $account->email     = $validated["email"];
        if(isset($validated["password"])){
            $account->password = Hash::make($validated["password"]);
        }
        $account->save();

        return back()->with("success","Perubahan pada data akun baru berhasil disimpan!");
    }

    public function upload(ImportAccountsRequest $request)
    {
        $temp = $request->file('accountFile' . $request->type)->store('temp');
        $path = storage_path('app') . '/' . $temp;

        $accounts = Excel::toCollection(new AccountsImport, $path);

        $db_emails = User::all()->pluck('email')->toArray();

        try {
            $response = (new AccountsStoreAction)->importAccounts($accounts, $db_emails);
        } catch (\Exception $exception) {
            return redirect()->back()
                ->with("failed", "Terjadi kesalahan saat akan menyimpan akun, harap pastikan fail sudah sesuai dengan panduan impor dan coba beberapa saat lagi.");
                // ->with("failed", $exception);
            } catch (\Error $error) {
            return redirect()->back()
                ->with("failed", "Terjadi kesalahan saat akan menyimpan akun, harap pastikan fail sudah sesuai dengan panduan impor dan coba beberapa saat lagi.");
                // ->with("failed", $error);
        }

        if(is_numeric($response)){
            return redirect()->back()
                ->with("success", "Sebanyak {$response} baris data akun baru berhasil disimpan.");
        }else{
            return redirect()->back()
                ->with("failed", $response);
        }
    }

    public function destroy(Request $request)
    {
        $account = User::find($request->user_id);
        $account->delete();

        return back()->with("success","Data akun berhasil dihapus!");
    }
}
