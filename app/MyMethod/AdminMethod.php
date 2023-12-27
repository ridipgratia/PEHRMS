<?php

namespace App\MyMethod;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
                    'desig_table.designation_name as designation_name',
                    'service_status_table.service_name as service_name',
                    'district_table.district_name as district_name',
                    'block_table.block_name as block_name',
                    'gp.gram_panchyat_name as gram_panchyat_name'
                )
                ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
                ->join('service_status as service_status_table', 'service_status_table.id', '=', 'main_table.service_status')
                ->join('districts as district_table', 'district_table.district_code', '=', 'main_table.posted_district')
                ->join('blocks as block_table', 'block_table.block_id', '=', 'main_table.posted_block')
                ->join('gram_panchyats as gp', 'gp.gram_panchyat_id', '=', 'main_table.posted_gp')
                ->orderBy('main_table.employe_name', 'asc')
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
    // public static function getEmployeeAllDetails($id)
    // {
    //     try {
    //         $employee_details = DB::table('employees as main_table')
    //             ->select(
    //                 'main_table.*',
    //                 'main_table.id as main_id',
    //                 'emp_bank.*',
    //                 'emp_service.*'
    //             )
    //             ->where('main_table.id', $id)
    //             ->leftJoin('employe_bank_details as emp_bank', 'emp_bank.employe_id', '=', 'main_table.id')
    //             ->leftJoin('employe_service_record as emp_service', 'emp_service.employe_id', '=', 'main_table.id')
    //             ->get();
    //         if (count($employee_details) != 0) {
    //             $employee_details[0]->password = NULL;
    //             try {
    //                 $employee_education_details = DB::table('employe_education_details')
    //                     ->where('employe_id', $id)
    //                     ->get();
    //                 $employee_details[1] = $employee_education_details;
    //                 return [true, $employee_details];
    //             } catch (Exception $err) {
    //                 return [false, 'Server Error Please Try Later'];
    //             }
    //         } else {
    //             return [false, 'Employe Details Not Found'];
    //         }
    //     } catch (Exception $err) {
    //         return [true, 'Server Error Please Try Later !'];
    //     }
    // }
    // Get Employee Dat Table Wise
    public static function getEmployeeDataTable($main_id, $table, $step_id)
    {
        try {
            $employee_data = [];
            if ($step_id == 1) {
                $employee_data = DB::table($table . ' as main_table')
                    ->select(
                        'main_table.*',
                        'main_table.id as main_id',
                        'desig_table.designation_name as designation_name',
                        'caste_table.caste as caste_name',
                        'ser_status_table.service_name as service_name',
                        'district_table.district_name as posted_district_name',
                        'block_table.block_name as posted_block_name',
                        'gp.gram_panchyat_name as posted_gram_panchyat_name',
                        'branche_table.branch_name as branch_name',
                        'district_table_1.district_name as district_name',
                        'block_table_1.block_name as block_name',
                        'gp_1.gram_panchyat_name as gram_panchyat_name',
                    )
                    ->where('main_table.id', $main_id)
                    ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
                    ->join('caste_list as caste_table', 'caste_table.id', '=', 'main_table.caste')
                    ->join('service_status as ser_status_table', 'ser_status_table.id', '=', 'main_table.service_status')
                    ->join('districts as district_table', 'district_table.district_code', '=', 'main_table.posted_district')
                    ->join('blocks as block_table', 'block_table.block_id', '=', 'main_table.posted_block')
                    ->join('gram_panchyats as gp', 'gp.gram_panchyat_id', '=', 'main_table.posted_gp')
                    ->join('branches as branche_table', 'branche_table.id', '=', 'main_table.branch')
                    ->join('districts as district_table_1', 'district_table_1.district_code', '=', 'main_table.district')
                    ->join('blocks as block_table_1', 'block_table_1.block_id', '=', 'main_table.block')
                    ->join('gram_panchyats as gp_1', 'gp_1.gram_panchyat_id', '=', 'main_table.gp')
                    ->get();
                if (count($employee_data) != 0) {
                    $employee_data[0]->password = NULL;
                    $employee_data[0]->state = "Assam";
                    $employee_data = json_decode(json_encode($employee_data), true);
                    $file_key = [
                        'employe_profile',
                        'employe_birth_certificate',
                        'pwd_document',
                        'order_document',
                        'current_joining_document',
                        'initial_appointment_letter',
                        'initial_joining_letter'
                    ];
                    foreach ($file_key as $file_value) {
                        $employee_data[0][$file_value] = Storage::url($employee_data[0][$file_value]);
                    }
                    // if (AdminMethod::getFileURL($employee_data, $file_key)) {
                    //     $employee_data = json_decode(json_encode($employee_data), false);
                    // } else {
                    //     throw new Exception('Error');
                    // }
                    $employee_data = json_decode(json_encode($employee_data), false);
                }
            } else if ($step_id == 4) {
                $employee_data = DB::table($table . ' as main_table')
                    ->select(
                        'main_table.*',
                        'desig_table.designation_name as promoted_to_designation_name',
                        'desig_table_1.designation_name as promoted_from_designation_name',
                        'district_table.district_name as transferred_from_district_name',
                        'block_table.block_name as transferred_from_block_name',
                        'gp.gram_panchyat_name as transferred_from_gp_name',
                        'district_table_1.district_name as transferred_to_district_name',
                        'block_table_1.block_name as transferred_to_block_name',
                        'gp_1.gram_panchyat_name as transferred_to_gp_name',
                        'ser_status_table.service_name as service_status_name',
                        'branch_table.branch_name as branch_name'
                    )
                    ->where('main_table.employe_id', $main_id)
                    ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.promoted_to_curr_des')
                    ->join('designations as desig_table_1', 'desig_table_1.id', '=', 'main_table.promoted_from_curr_des')
                    ->join('districts as district_table', 'district_table.district_code', '=', 'main_table.transferred_from_district')
                    ->join('blocks as block_table', 'block_table.block_id', '=', 'main_table.transferred_from_block')
                    ->join('gram_panchyats as gp', 'gp.gram_panchyat_id', '=', 'main_table.transferred_from_gp')
                    ->join('districts as district_table_1', 'district_table_1.district_code', '=', 'main_table.transferred_to_district')
                    ->join('blocks as block_table_1', 'block_table_1.block_id', '=', 'main_table.transferred_to_block')
                    ->join('gram_panchyats as gp_1', 'gp_1.gram_panchyat_id', '=', 'main_table.transferred_to_gp')
                    ->join('service_status as ser_status_table', 'ser_status_table.id', '=', 'main_table.service_branch')
                    ->join('branches as branch_table', 'branch_table.id', '=', 'main_table.service_branch')
                    ->get();
            } else {
                $employee_data = DB::table($table)
                    ->where('employe_id', $main_id)
                    ->get();
            }
            return $employee_data;
        } catch (Exception $err) {
            return NULL;
        }
    }
    // Convert String URL 
    public static function getFileURL($data, $file_key)
    {
        try {
            foreach ($file_key as $file_value) {
                $data[0][$file_value] = Storage::url($data[0][$file_value]);
            }
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    // Search Filter On One Input Call Method
    public static function searchOnOneInput($search_query)
    {
        try {
            $employees = DB::table('employees as main_table')
                ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
                ->join('service_status as service_status_table', 'service_status_table.id', '=', 'main_table.service_status')
                ->join('districts as district_table', 'district_table.district_code', '=', 'main_table.posted_district')
                ->join('blocks as block_table', 'block_table.block_id', '=', 'main_table.posted_block')
                ->join('gram_panchyats as gp', 'gp.gram_panchyat_id', '=', 'main_table.posted_gp')
                ->join('levels as level_table', 'level_table.id', '=', 'main_table.level_id')
                ->orWhere('main_table.employe_code', 'like', '%' . $search_query . '%')
                ->orWhere('main_table.employe_name', 'like', '%' . $search_query . '%')
                ->orWhere('desig_table.designation_name', 'like', '%' . $search_query . '%')
                ->orWhere('service_status_table.service_name', 'like', '%' . $search_query . '%')
                ->select(
                    'main_table.id as main_id',
                    'main_table.employe_code',
                    'main_table.employe_name',
                    'main_table.employe_designation',
                    'main_table.service_status',
                    'main_table.employe_phone',
                    'main_table.employe_email',
                    'desig_table.designation_name as designation_name',
                    'service_status_table.service_name as service_name',
                    'district_table.district_name as district_name',
                    'block_table.block_name as block_name',
                    'gp.gram_panchyat_name as gram_panchyat_name',
                    'level_table.level_name as level_name'
                )
                ->orderBy('main_table.employe_name', 'asc')
                ->get();
            return $employees;
        } catch (Exception $err) {
            return NULL;
        }
    }
    // Search By Many Select Input
    public static function searchByManySelectMethod($search_keys)
    {
        $check = false;
        try {
            $search_query = DB::table('employees as main_table')
                ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
                ->join('districts as district_table', 'district_table.district_code', '=', 'main_table.posted_district')
                ->join('blocks as block_table', 'block_table.block_id', '=', 'main_table.posted_block')
                ->join('gram_panchyats as gp', 'gp.gram_panchyat_id', '=', 'main_table.posted_gp')
                ->join('levels as level_table', 'level_table.id', '=', 'main_table.level_id');
            foreach ($search_keys as $search_key => $search_value) {
                if ($search_value) {
                    $search_query->where('main_table.' . $search_key, $search_value);
                }
            }
            $search_filters = $search_query
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
                    'district_table.district_name as district_name',
                    'block_table.block_name as block_name',
                    'gp.gram_panchyat_name as gram_panchyat_name',
                    'level_table.level_name as level_name'
                )
                ->orderBy('main_table.employe_name', 'asc')
                ->get();
            return $search_filters;
        } catch (Exception $err) {
            $check = NULL;
        }
    }
    // Export Employees PDF Method 
    public static function exportEmployeesPDFMethod($check_filter_id)
    {
        $sql_query = DB::table('employees as main_table');
        $pre_sql = $sql_query
            ->join('designations as desig_table', 'desig_table.id', '=', 'main_table.employe_designation')
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
                'district_table.district_name as district_name',
                'block_table.block_name as block_name',
                'gp.gram_panchyat_name as gram_panchyat_name',
                'level_table.level_name as level_name'
            );
        if ($check_filter_id == 1) {
            $employees = $pre_sql
                ->orderBy('main_table.employe_name', 'asc')
                ->get();
            return $employees;
        }
    }
}
