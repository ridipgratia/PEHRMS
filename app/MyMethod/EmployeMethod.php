<?php

namespace App\MyMethod;

use App\Models\EmployeModel;
use Illuminate\Support\Facades\DB;

class EmployeMethod
{
    public static function checkEmployeData($table, $column_name, $data)
    {
        $check_email = DB::table($table)
            ->where($column_name, $data)
            ->select('id')
            ->get();
        if (count($check_email) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public static function generateEmpCode($level_code)
    {
        $main_text = "PEHRMS";
        $last_id = DB::table('employe')
            ->orderBy('id', 'desc')->first();
        $year = date('Y');
        $emp_code = $main_text . $level_code . $year . $last_id->id;
        return $emp_code;
    }
}
