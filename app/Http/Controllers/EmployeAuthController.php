<?php

namespace App\Http\Controllers;

use App\Models\EmployeModel;
use App\MyMethod\EmailSender;
use App\MyMethod\EmployeMethod;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeAuthController extends Controller
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
            "required" => 'Fill All Input Fileds ',
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
    // Employe Login
    public function login(Request $request)
    {
        $login_data = [
            'email' => $request->email,
            'password' => $request->password,
            'level_id' => $request->level_id
        ];
        $status = 400;
        $message = null;
        if (Auth::guard('employe')->attempt($login_data)) {
            $check = false;
            try {
                $login_user = EmployeModel::where('email', $request->email)->first();
                $check = true;
            } catch (Exception $err) {
                $check = false;
            }
            if ($check) {
                Auth::login($login_user);
                $employe = Auth::user();
                $token = $login_user->createToken('EmployeToken')->accessToken;
                $status = 200;
                return response()->json(['status' => $status, 'token' => $token], 200);
            } else {
                $message = "Server Error Try Later !";
            }
        } else {
            $message = "User Credentials Not Found !";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    // Employe Profile
    public function profile(Request $request)
    {
        return response()->json(['status' => 200, 'message' => 'User Authorized', 'employe_data' => Auth::user()], 200);
    }
    // Employe Logout 
    public function logout(Request $request)
    {
        if (auth()->user()) {
            Auth::user()->tokens->each(function ($token) {
                $token->revoke();
            });
        }
        return response()->json(['message' => 'Logout User '], 200);
    }
    // Employe OTP Login
    public function otp_login(Request $request)
    {

        $login_email = $request->email;
        $status = 400;
        $message = null;
        if ($login_email == null) {
            $message = "Email ID Required ";
        } else {
            $login_employe = EmployeModel::where('email', $login_email)->first();
            if ($login_employe) {
                $otp = rand(1000, 9999);
                $emailData = ['otp' => $otp, 'name' => $login_employe->name, 'subject' => 'Employe Login OTP'];
                date_default_timezone_set('Asia/Kolkata');
                $send_time = date('Y-m-d H:i:s');
                $save_otp_data = [
                    'email' => $login_employe->email,
                    'expire_time' => $send_time,
                    'otp' => $otp,
                    'created_at' => $send_time,
                    'updated_at' => $send_time
                ];
                $check = EmailSender::saveOTP('employe_lotp', $save_otp_data);
                if ($check) {
                    $check = EmailSender::emailSend($emailData, $login_employe->email, 'employe_otp');
                    if ($check) {
                        $status = 200;
                        $message = "OTP Send Successfully In Your Email !";
                    } else {
                        $message = "Email Not Send Try Again !";
                    }
                } else {
                    $message = "Try Later Server Error !";
                }
                // $expire_time = $login_employe->created_at;
                // $send_time_stamp = new DateTime($send_time);
                // $diff = $send_time_stamp->diff(new DateTime($expire_time));
                // $diff = [$diff->d, $diff->h, $diff->i];
                return response()->json(['status' => $status, 'message' => $message], 200);
            } else {
                $message = "Email ID Not Found !";
            }
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }

    // Employe OTP Verify And Login 
    public function otp_verify_login(Request $request)
    {
        $login_email = $request->email;
        $employe_otp = $request->employe_otp;
        $status = 400;
        $message = null;
        $check = false;
        if ($login_email == null || $employe_otp == null) {
            $message = "OTP Required !";
        } else {
            $login_employe = EmployeModel::where('email', $login_email)->first();
            if ($login_employe) {
                try {
                    $otp_data = DB::table('employe_lotp')
                        ->where('email', $login_employe->email)
                        ->get();
                    $check = true;
                } catch (Exception $err) {
                    $check = false;
                }
                if ($check) {
                    if (count($otp_data) == 0) {
                        $message = "Re Generate OTP ! OTP Lost !";
                    } else {
                        date_default_timezone_set('Asia/Kolkata');
                        $expire_time = $otp_data[0]->expire_time;
                        $revice_time = date('Y-m-d H:i:s');
                        $new_expire_time = new DateTime($expire_time);
                        $time_diff = $new_expire_time->diff(new DateTime($revice_time));
                        if ($time_diff->y == 0 & $time_diff->m == 0 && $time_diff->d == 0 && $time_diff->h == 0 && $time_diff->i <= 20) {
                            if ($otp_data[0]->otp == $employe_otp) {
                                Auth::login($login_employe);
                                $employe = Auth::user();
                                $token = $login_employe->createToken('EmployeToken')->accessToken;
                                $status = 200;
                                return response()->json(['status' => $status, 'token' => $token, 'data' => $employe], 200);
                            } else {
                                $message = "OTP Not Currect !";
                            }
                        } else {
                            $message = "OTP Is Expired !";
                        }
                    }
                } else {
                    $message = "Try Later Server Error !";
                }
            } else {
                $message = "Email Not Identify !";
            }
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
}
