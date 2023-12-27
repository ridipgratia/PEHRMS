<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportEmployeesExcel implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     //
    // }
    public $check_filter_type;
    public function __construct($check_filter_type)
    {
        $this->check_filter_type = $check_filter_type;
    }
    public function view(): View
    {
        if ($this->check_filter_type == 1) {
            $employees = $this->allEmployeeData();
        }
        return view('exports.employees_excel', [
            'employees' => $employees
        ]);
    }
    public function allEmployeeData()
    {
        $employees = DB::table('employees')
            ->orderBy('employe_name', 'asc')
            ->get();
        return $employees;
    }
}
