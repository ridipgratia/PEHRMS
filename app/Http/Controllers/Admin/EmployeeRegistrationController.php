<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeModel;
use App\MyMethod\AdminMethod;
use App\MyMethod\EmailSender;
use App\MyMethod\EmployeMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeRegistrationController extends Controller
{
    public function register(Request $request)
    {
        // Employe Registration 
        // $employe = EmployeModel::create([
        //     'emp_code' => $request->emp_code,
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone' => $request->phone,
        //     'district_code' => $request->district_code,
        //     'block_code' => $request->block_code,
        //     'gp_code' => $request->gp_code,
        //     'level_id' => $request->level_id,
        // ]);
        $status = 400;
        $message = [];
        $error_message = [
            "name.required" => 'Name Is Required ',
            "email.required" => 'Email ID Required ',
            "phone.required" => 'Phone Number Is Required ',
            "state_code.required" => 'State Code Is Required ',
            "integer" => "Code Should Be Numeric",
            "email" => "Enter A Valid Email",
            "min" => "Phone Number Must Be 10 Digits",
            "max" => "Phone Number Must Be 10 Digits"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|min:10|max:10',
                'state_code' => 'required'
            ],
            $error_message
        );
        if ($validator->fails()) {
            array_push($message, $validator->errors()->all());
        } else {
            $check_employe_data = EmployeMethod::checkEmployeData('employe', 'email', $request->email);
            if ($check_employe_data) {
                $check_employe_data = EmployeMethod::checkEmployeData('employe', 'phone', $request->phone);
                if ($check_employe_data) {
                    $level_code = null;
                    if ($request->state_code) {
                        if ($request->district_code) {
                            if ($request->block_code) {
                                if ($request->gp_code) {
                                    $level_code = "GP";
                                } else {
                                    $level_code = "BL";
                                }
                            } else {
                                $level_code = "DT";
                            }
                        } else {
                            $level_code = "ST";
                        }
                    }
                    if ($level_code) {
                        $password = EmployeMethod::generatePassword();
                        $emp_code = EmployeMethod::generateEmpCode($level_code);
                        $check = false;
                        try {
                            // $employe_id = DB::table('employe')
                            //     ->insertGetId([
                            //         'emp_code' => $emp_code . (DB::select('select id from employe') == null ? 0 : DB::select('select id from employe order by id desc limit 1')[0]->id),
                            //         'name' => $request->name,
                            //         'email' => $request->email,
                            //         'password' => Hash::make($password),
                            //         'phone' => $request->phone,
                            //         'district_code' => $request->district_code,
                            //         'block_code' => $request->block_code,
                            //         'gp_code' => $request->gp_code,
                            //         'level_id' => $request->level_id,
                            //     ]);
                            $employe_save = EmployeModel::create([
                                'emp_code' => $emp_code . (DB::select('select id from employe') == null ? 0 : DB::select('select id from employe order by id desc limit 1')[0]->id),
                                'name' => $request->name,
                                'email' => $request->email,
                                'password' => Hash::make($password),
                                'phone' => $request->phone,
                                'district_code' => $request->district_code,
                                'block_code' => $request->block_code,
                                'gp_code' => $request->gp_code,
                                'level_id' => $request->level_id,
                            ]);
                            $check = true;
                        } catch (Exception $err) {
                            $check = false;
                        }
                        if ($check) {
                            // array_push($message, ['Done']);
                            $employe_data = [
                                'name' => $employe_save->name,
                                'emp_code' => $employe_save->emp_code,
                                'email' => $employe_save->email,
                                'password' => $password,
                                'subject' => 'Employe Registration'
                            ];
                            $check_email_send = EmailSender::emailSend($employe_data, $employe_save->email, 'employe_register');
                            if ($check_email_send) {
                                $status = 200;
                                array_push($message, [$employe_save, 'Registration Completed ']);
                            } else {
                                array_push($message, ['Registration Completed But Email Not Send !']);
                            }
                        } else {
                            array_push($message, ['Try Again Registration Not Completed']);
                        }
                    } else {
                        array_push($message, ['Select Your Level']);
                    }
                } else {
                    array_push($message, ['Phone Already Registered !']);
                }
            } else {
                $done = ["ok"];
                array_push($message, ['Email Already Registered !']);
            }
        }
        // $token = $employe->createToken('EmployeToken')->accessToken;
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    public function getDistricts(Request $request)
    {
        $status = 400;
        $message = "";
        if (AdminMethod::getAllDistricts()) {
            $districts = AdminMethod::getAllDistricts();
            $status = 200;
            return response()->json(['status' => $status, 'districts' => $districts], 200);
        } else {
            $message = "Server Error Try Later !";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    public function getBlocks(Request $request)
    {
        $status = 400;
        $message = "";
        $district_code = $request->district_code;
        if ($district_code) {
            $blocks = AdminMethod::getAllBlocks($district_code);
            if ($blocks) {
                if (count($blocks) == 0) {
                    $message = "No Blocks Found ";
                } else {
                    $status = 200;
                    return response()->json(['status' => $status, 'blocks' => $blocks], 200);
                }
            } else {
                $message = "Server Error Try Later !";
            }
        } else {
            $message = "Didn't Recieve District Code ";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    public function getGPs(Request $request)
    {
        $status = 400;
        $message = "";
        $block_code = $request->block_code;
        if ($block_code) {
            $gps = AdminMethod::getAllGPs($block_code);
            if ($gps) {
                if (count($gps) == 0) {
                    $message = "No GP found!";
                } else {
                    $status = 200;
                    return response()->json(['status' => $status, 'gps' => $gps], 200);
                }
            } else {
                $message = "Server Error Try Later !";
            }
        } else {
            $message = "Didn't Recieve Block Code ";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
}
