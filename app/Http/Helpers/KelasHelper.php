<?php

namespace App\Http\Helpers;

use App\Models\Kelas;

class KelasHelper extends GeneralHelper{

    function generate_enrollment_key(){
        $characters = "1234567890!@#$%^&*()QWERTYUIOPASDFGHJKLZXCVBNM[];',./-=qwertyuiopasdfghjklzxcvbnm{}:<>?";
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

        $existing_enrollment_key_list = $this->get_col_from_table("enrollment_key", "kelas");
        return in_array($temp_key, $existing_enrollment_key_list);
    }

    function get_class_id_from_enrollment_key($enrollment_key){

        $kelas = Kelas::where("enrollment_key", $enrollment_key)->first();

        if (count($kelas) == 0){
            return $kelas->id;
        }

        return null;
    }
}

?>