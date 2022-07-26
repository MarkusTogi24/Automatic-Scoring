<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralHelper{

    public function authorizing_by_role($authorized_role){
        if (gettype($authorized_role) == "string"){
            if ($authorized_role != Auth::user()->role){
                abort(403);
            }
        }else if (gettype($authorized_role) == "array"){
            if (!in_array(Auth::user()->role, $authorized_role)){
                abort(403);
            }
        }
    }

    public function authorizing_by_user_id($members){
        if (!in_array(Auth::user()->id, $members)){
            abort(403);
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