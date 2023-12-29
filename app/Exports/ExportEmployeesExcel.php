<?php

namespace App\Exports;

use Exception;
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
    public $search_element;
    public $sql_query;
    public $pre_sql;
    public function __construct($check_filter_type, $search_element)
    {
        $this->check_filter_type = $check_filter_type;
        $this->search_element = $search_element;
        $this->sql_query = DB::table('employees as main_table');
        $this->pre_sql = $this->sql_query
            ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
            ->join('service_status as service_status_table', 'service_status_table.id', '=', 'main_table.service_status')
            ->join('districts as district_table', 'district_table.district_code', '=', 'main_table.posted_district')
            ->join('blocks as block_table', 'block_table.block_id', '=', 'main_table.posted_block')
            ->join('gram_panchyats as gp', 'gp.gram_panchyat_id', '=', 'main_table.posted_gp')
            ->join('levels as level_table', 'level_table.id', '=', 'main_table.level_id')
            ->select(
                'main_table.id as main_id',
                'main_table.employe_code',
                'main_table.employe_name',
                'main_table.employe_designation',
                'main_table.service_status',
                'main_table.employe_phone',
                'main_table.employe_email',
                'main_table.level_id',
                'desig_table.designation_name as designation_name',
                'service_status_table.service_name as service_name',
                'district_table.district_name as district_name',
                'block_table.block_name as block_name',
                'gp.gram_panchyat_name as gram_panchyat_name',
                'level_table.level_name as level_name'
            );
    }
    public function view(): View
    {
        if ($this->check_filter_type == 1) {
            try {
                $employees = $this->allEmployeeData();
            } catch (Exception $err) {
                $employees = [];
            }
        } else if ($this->check_filter_type == 2) {
            try {
                $employees = $this->onInputSearch($this->search_element);
            } catch (Exception $err) {
                $employees = [];
            }
        } else if ($this->check_filter_type == 3) {
            try {
            } catch (Exception $err) {
                $employees = [];
            }
        }
        return view('exports.employees_excel', [
            'employees' => $employees
        ]);
    }
    public function allEmployeeData()
    {
        $employees =
            $this->pre_sql
            ->orderBy('main_table.employe_name', 'asc')
            ->get();
        return $employees;
    }
    public function onInputSearch($search_query)
    {
        $employees =
            $this->pre_sql
            ->orWhere('main_table.employe_code', 'like', '%' . $search_query . '%')
            ->orWhere('main_table.employe_name', 'like', '%' . $search_query . '%')
            ->orWhere('desig_table.designation_name', 'like', '%' . $search_query . '%')
            ->orWhere('service_status_table.service_name', 'like', '%' . $search_query . '%')
            ->orderBy('main_table.employe_name', 'asc')
            ->get();
        return $employees;
    }
}
