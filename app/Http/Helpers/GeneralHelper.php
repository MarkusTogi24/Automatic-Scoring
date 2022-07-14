<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralHelper{

    function authorize($authorize_role){
        
        if (gettype($authorize_role) == "array"){
            if (!in_array(Auth::user()->role, $authorize_role)){
                abort(403);
            }
        }else if (gettype($authorize_role) == "string"){
            if (Auth::user()->role != $authorize_role){
                abort(403);
            }
        }
    }

    function get_col_from_table($col_name, $table_name){
        if (gettype($col_name) == "array"){
            $col_name = join(", ", $col_name);
        }
        return DB::select(DB::raw("SELECT ".$col_name." FROM ".$table_name.";"));    
    }
}
?>