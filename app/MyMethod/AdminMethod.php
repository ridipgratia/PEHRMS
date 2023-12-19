<?php

namespace App\MyMethod;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AdminMethod
{
    public static function getAllDistricts()
    {
        try {
            $districts = DB::table('districts')
                ->orderBy('district_name', 'asc')
                ->select('district_code', 'district_name')
                ->get();
            return $districts;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function getAllBlocks($district_code)
    {
        try {
            $blocks = DB::table('blocks')
                ->where('district_id', $district_code)
                ->select('block_id', 'block_name')
                ->orderBy('block_name', 'asc')
                ->get();
            return $blocks;
        } catch (Exception $err) {
            return false;
        }
    }

    public static function getAllGPs($block_code)
    {

        try {
            $gram_panchayats = DB::table('gram_panchyats')
                ->where('block_id', $block_code)
                ->select('gram_panchyat_id', 'gram_panchyat_name')
                ->orderBy('gram_panchyat_name', 'asc')
                ->get();
            return $gram_panchayats;
        } catch (Exception $err) {
            return false;
        }
    }
    // get All Employees List
    public static function getAllEmployees()
    {
        try {
            $employees = DB::table('employees as main_table')
                ->select(
                    'main_table.id as main_id',
                    'main_table.employe_code',
                    'main_table.employe_name',
                    'main_table.employe_designation',
                    'main_table.service_status',
                    'main_table.employe_phone',
                    'main_table.employe_email',
                    'desig_table.designation_name as designation_name'
                )
                ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
                ->get();
            // $employees = $employees->map(function ($employe) {
            //     $employe->id = Crypt::encryptString($employe->id);
            //     return $employe;
            // });
            return $employees;
        } catch (Exception $err) {
            return NULL;
        }
    }
    // View Particular Employe Details
    public static function getEmployeeAllDetails($id)
    {
        try {
            $employee_details = DB::table('employees as main_table')
                ->select(
                    'main_table.*',
                    'main_table.id as main_id',
                    'emp_bank.*',
                    'emp_service.*'
                )
                ->where('main_table.id', $id)
                ->leftJoin('employe_bank_details as emp_bank', 'emp_bank.employe_id', '=', 'main_table.id')
                ->leftJoin('employe_service_record as emp_service', 'emp_service.employe_id', '=', 'main_table.id')
                ->get();
            if (count($employee_details) != 0) {
                $employee_details[0]->password = NULL;
                try {
                    $employee_education_details = DB::table('employe_education_details')
                        ->where('employe_id', $id)
                        ->get();
                    $employee_details[1] = $employee_education_details;
                    return [true, $employee_details];
                } catch (Exception $err) {
                    return [false, 'Server Error Please Try Later'];
                }
            } else {
                return [false, 'Employe Details Not Found'];
            }
        } catch (Exception $err) {
            return [true, 'Server Error Please Try Later !'];
        }
    }
}
