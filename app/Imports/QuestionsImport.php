<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class QuestionsImport implements ToCollection, SkipsEmptyRows
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
