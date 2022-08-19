<?php

namespace App\Action;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AccountsStoreAction
{
    public function importAccounts(Collection $accounts, array $db_emails): int|string
    {
        $existInDbMessage   = "Email pada nomor ";
        $existInDbCount     = 0;

        $invalidRoleMessage = "Peran pada nomor ";
        $invalidRoleCount   = 0;

        $emptyCellCount     = 0;

        $input_email = array();

        foreach ($accounts[0] as $index => $account) {
            if($index == 0) continue;

            $input_email[] = $account[2];

            // CHECK IF THERE'S AN EMPTY CELL IN A ROW
            if( $account[1] == null || $account[2] == null || $account[3] == null || $account[4] == null ){
                $emptyCellCount++;
            }
            
            // CHECK IF EMAIL EXIST IN DB
            if (in_array( $account[2], $db_emails )) {
                $existInDbMessage .= $account[0] . ", ";
                $existInDbCount++;
            }

            // CHECK THE ROLE WHETHER IT'S NOT "SISWA" OR IT'S NOT "GURU"
            $role = strtoupper( trim($account[4]) );
            if ( !in_array( $role, ["SISWA", "GURU"] ) ) {
                $invalidRoleMessage .= $account[0] . ", ";
                $invalidRoleCount++;
            }
        }

        // CHECK EMAIL DUPLICATION
        $duplicateEmailMessage = "Terdapat lebih dari satu data dengan email ";
        $duplicates = array();
        foreach(array_count_values($input_email) as $value => $count){
            if($count > 1){
                $duplicates[] = $value;
                $duplicateEmailMessage .= $value . ", ";
            } 
        }
        if($emptyCellCount > 0){ // RETURN IF THERE IS AN EMPTY CELL IN A ROW
            return "Masih terdapat sel data yang kosong, harap periksa fail sebelum diunggah.";
        }else if($existInDbCount > 0){ // RETURN IF THERE IS EMAIL THAT ALREADY EXIST IN DB
            $existInDbMessage .= "sudah digunakan, harap periksa fail sebelum diunggah.";
            return $existInDbMessage;
        }else if ($invalidRoleCount > 0) { // RETURN IF THERE IS ROLE THAT INVALID
            $invalidRoleMessage .= "tidak valid, harap pastikan peran yang dimasukkan adalah \"GURU\" atau \"SISWA\".";
            return $invalidRoleMessage;
        }else if (sizeof($duplicates) > 0) { // RETURN IF THERE IS EMAIL DUPLICATION
            $duplicateEmailMessage .= "harap periksa fail sebelum diunggah.";
            return $duplicateEmailMessage;
        }else{
            $savedAccount = 0;
            foreach ($accounts[0] as $index => $account) {
                if($index == 0) continue;
                $new_account                = new User;
                $new_account->name          = $account[1];
                $new_account->email         = $account[2];
                $new_account->password      = Hash::make($account[3]);
                $new_account->role          = strtoupper( trim($account[4]) );
                $new_account->save();

                $savedAccount++;
            }
            return $savedAccount;
        }
        
    }
    
}
