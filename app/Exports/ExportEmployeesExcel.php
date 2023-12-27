<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

// class ExportEmployeesExcel implements FromCollection
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */
//     // public function collection()
//     // {
//     //     //
//     // }
//     public function view(): View
//     {
//         $employees = DB::table('employees')
//             ->orderBy('employe_name', 'asc')
//             ->get();
//     }
// }
