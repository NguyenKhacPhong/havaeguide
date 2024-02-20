<?php

namespace App\Imports;

use App\Models\Major;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelImportMajors implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Major([
            'major_name' => $row[0],
            'major_description' => $row[1],
            'sector_id' => $row[2],
        ]);
    }
}
