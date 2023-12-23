<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MyMethod\AdminMethod;
use Exception;
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
        $status = 400;
        $message = null;
        if ($request->main_id && $request->step_id) {
            $employee_data = AdminMethod::getEmployeeDataTable($employee_id, $tables[$step_id - 1], $step_id);
            if ($employee_data == NULL) {
                $message = "Server Error Please Try Again !";
            } else {
                return response()->json(['status' => 200, "data" => $employee_data], 200);
            }
        } else {
            $message = "Required Input Are Not Found ";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    // Search Filter On One Input
    public function searchOnOneInput(Request $request)
    {
        $status = 400;
        if ($request->search_query) {
            $filter_data = AdminMethod::searchOnOneInput($request->search_query);
            if ($filter_data) {
                return response()->json(['status' => $status, 'data' => $filter_data], 200);
            } else {
                return response()->json(['status' => $status, 'message' => 'Try Later Database Try'], 200);
            }
        }
        return response()->json(['status' => 200, 'message' => '']);
    }
    // Search By Many Select Input 
    public function searchByManySelect(Request $request)
    {
        $search_keys = [
            'district' => $request->district,
            'block' => $request->block,
            'gp' => $request->gp,
            'level_id' => $request->level_id,
            'employe_designation' => $request->employe_designation,
            'service_status' => $request->service_status
        ];
        $search_filters = AdminMethod::searchByManySelectMethod($search_keys);
        if ($search_filters) {
            return response()->json(['status' => 200, 'data' => $search_filters], 200);
        } else {
            return response()->json(['status' => 400, 'message' => "Server Error Try Later !"], 200);
        }
    }
}
