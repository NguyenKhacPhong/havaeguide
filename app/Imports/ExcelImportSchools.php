<?php

namespace App\Imports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelImportSchools implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new School([
            'school_code' => $row[0],
            'school_name' => $row[1],
            'school_address' => $row[2],
            'school_phone' => $row[3],
            'school_email' => $row[4],
            'school_website' => $row[5],
            'school_description' => $row[6],
            'school_detail' => $row[7],
            'type_id' => $row[8],
            'area_id' => $row[9]
        ]);
    }
}
