<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = collect();

        foreach ($rows as $row) {
            $data = $row[0];
        }

        return $data;
    }
}