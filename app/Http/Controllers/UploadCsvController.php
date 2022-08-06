<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadCsvController extends Controller
{
    //

    public function upload(Request $request){

        $file = $request->file('csvfile');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $csv = fopen($file, 'r');
        while (($filedata = fgetcsv($csv)) != false){
            $num = count($filedata);
            echo $num;
            // var_dump($filedata[0]);
            // for ($i = 0 ; $i < $num ; $i ++){
            //     echo $filedata[$i];
            // }
        }
    }
}
