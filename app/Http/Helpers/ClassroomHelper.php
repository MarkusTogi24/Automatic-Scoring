<?php

namespace App\Http\Helpers;

use App\Models\Classroom;
use App\Models\ClassroomAndMember;
use Illuminate\Support\Facades\Auth;

class ClassroomHelper extends GeneralHelper{

    function generate_enrollment_key(){
        $characters = "1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm";
        $random_string = "";
        $is_get_enrollment_key = false;

        while (!$is_get_enrollment_key){
            for ($i = 0; $i < 10; $i++){
                $index = rand(0, strlen($characters) - 1);
                $random_string .= $characters[$index];
            }

            if (!$this->is_key_exist($random_string)){
                $is_get_enrollment_key = true;
            }
        }

        return $random_string;
    }

    function is_key_exist($temp_key){

        $is_exist = Classroom::where("enrollment_key", $temp_key)->first();
        return $is_exist != null;
    }

    function get_class_id_from_enrollment_key($enrollment_key){

        $kelas = Classroom::where("enrollment_key", $enrollment_key)->first();

        if (count($kelas) == 0){
            return $kelas->id;
        }

        return null;
    }

    function authorizing_classroom_member($class_id){
        $row = ClassroomAndMember::all()->where('classroom_id', $class_id)->where('member_id', Auth::user()->id);
        
        if (count($row) < 1) {
            abort(403);
        }
    }
}


?>