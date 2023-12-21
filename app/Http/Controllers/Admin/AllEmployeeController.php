<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MyMethod\AdminMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AllEmployeeController extends Controller
{
    // View All Employees
    public function allEmployees(Request $request)
    {
        $message = null;
        $all_employees = AdminMethod::getAllEmployees();
        if ($all_employees) {
            return response()->json(['status' => 200, 'employees' =>  $all_employees], 200);
        } else {
            $message = "Server Error Please try Later !";
        }
        return response()->json(['status' => 400, 'message' => $message], 200);
    }
    // View Specific Employee Details
    // public function viewEmployee(Request $request)
    // {
    //     $message = null;
    //     $status = 400;
    //     $id = $request->id;
    //     $employee_details = AdminMethod::getEmployeeAllDetails($id);
    //     if ($employee_details[0]) {
    //         $status = 200;
    //         return response()->json(['status' => $status, 'employee_details' => $employee_details[1]]);
    //     }
    //     return response()->json(['status' => $status, 'message' => $employee_details[1]]);
    // }

    // Get All Employee Table Wise 
    public function getAllEmployeeDetails(Request $request)
    {
        $employee_id = $request->main_id;
        $step_id = $request->step_id;
        $tables = [
            'employees',
            'employe_bank_details',
            'employe_education_details',
            'employe_service_record'
        ];
        return response()->json(['status' => 400, 'message' => 'Ok'], 200);
    }
}
