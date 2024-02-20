<?php

namespace App\Exports;

use App\Models\Major;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelExportMajors implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Major::all();
    }
}
