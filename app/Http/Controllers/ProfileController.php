<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Profile\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('pages.user.profile.index');
    }

    public function edit()
    {
        return view('pages.user.profile.edit');
    }

    // public function update(Request $request)
    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();

        $user = User::find(Auth::user()->id);
        
        if(isset($validated["profile_picture"])){
            if(Storage::exists($user->profile_picture)){
                Storage::delete($user->profile_picture);
            }
            $storeImage                     = $request->file('profile_picture')->store('profile-pictures');
            $validated["profile_picture"]   = $storeImage;
            $user->profile_picture          = $validated["profile_picture"];
        }

        $user->name     = $validated["name"];
        $user->email    = $validated["email"];

        if(isset($validated["new_password"])){
            $user->password = Hash::make($validated["new_password"]);
        }

        $user->save();

        return redirect()->route('profile.index')
            ->with("success","Perubahan pada profil anda berhasil disimpan!");

    }
}
