<?php

namespace App\Http\Controllers;

use App\Models\EmployeesModel;
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
    // Employe Login
    public function login(Request $request)
    {
        $login_data = [
            'employe_email' => $request->email,
            'password' => $request->password,
            'level_id' => $request->level_id
        ];
        $status = 400;
        $message = null;
        if (Auth::guard('employe')->attempt($login_data)) {
            $check = false;
            try {
                $login_user = EmployeesModel::where('employe_email', $request->email)->first();
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
            $login_employe = EmployeesModel::where('employe_email', $login_email)->first();
            if ($login_employe) {
                $otp = rand(1000, 9999);
                $emailData = ['otp' => $otp, 'name' => $login_employe->employe_code, 'subject' => 'Employe Login OTP'];
                date_default_timezone_set('Asia/Kolkata');
                $send_time = date('Y-m-d H:i:s');
                $save_otp_data = [
                    'email' => $login_employe->employe_email,
                    'expire_time' => $send_time,
                    'otp' => $otp,
                    'created_at' => $send_time,
                    'updated_at' => $send_time
                ];
                $check = EmailSender::saveOTP('employe_lotp', $save_otp_data);
                if ($check) {
                    $check = EmailSender::emailSend($emailData, $login_employe->employe_email, 'employe_otp');
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
            $login_employe = EmployeesModel::where('employe_email', $login_email)->first();
            if ($login_employe) {
                try {
                    $otp_data = DB::table('employe_lotp')
                        ->where('email', $login_employe->employe_email)
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
    // Reset Employee Password 
    // Reset Password Link Apply
    public function resetPassword(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;
        $message = "";
        $status = 400;
        $check_response = EmployeMethod::checkEmailRegistered($email);
        if ($check_response[0]) {
            if ($check_response[1] == 1) {
                $check_response = EmployeMethod::checkResetPasswordEmail($email);
                if ($check_response[0]) {
                    $apply_url_res = EmployeMethod::setResetPasswordData($email, $check_response[1]);
                    if ($apply_url_res) {
                        $emailData = [
                            'url' => 'http://localhost:5173/verify-reset-password/' . $apply_url_res,
                            'email' => $email,
                            'subject' => 'Password Reset Apply Link '
                        ];
                        $check = EmailSender::emailSend($emailData, $email, 'reset_pasword');
                        if ($check) {
                            $message = "Check Reet Password Link In Your Email";
                            $status = 200;
                        } else {
                            $message = "Email Not Send Try Again";
                        }
                    } else {
                        $message = "Try Again To Reset Password !";
                    }
                } else {
                    $message = "Server Error Please Try Later !";
                }
            } else {
                $message = "Enter a Registered Email ID";
            }
        } else {
            $message = "Server Error Please  Try Later !";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    // Verify Reset Password Link
    public function verifyResetPasswordLink(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $url = $request->url;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        $message = [];
        $status = 400;
        $error_message = [
            'required' => ':attribute is required field',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required',
                'confirm_password' => 'required',
                'url' => 'required'
            ],
            $error_message
        );
        if ($validator->fails()) {
            array_push($message, $validator->errors()->all());
        } else {
            $check_res = EmployeMethod::checkResetPassLinkValid($url);
            if ($check_res[0]) {
                if (count($check_res[1]) == 1) {
                    $new_expire_time = new DateTime($check_res[1][0]->expire_time);
                    $recive_time = date('Y-m-d H:i:s');
                    $time_diff = $new_expire_time->diff(new DateTime($recive_time));
                    if ($time_diff->y == 0 & $time_diff->m == 0 && $time_diff->d == 0 && $time_diff->h == 0 && $time_diff->i <= 20) {
                        if ($check_res[1][0]->active == 1) {
                            if ($password === $confirm_password) {
                                if (EmployeMethod::updateResetPassword($url, $check_res[1][0]->email, $password)) {
                                    array_push($message, ['Password Changed Successfully']);
                                } else {
                                    array_push($message, ['Password Not Change ! Try Again ']);
                                }
                            } else {
                                array_push($message, ['Your Confirm Password Does Not Matched ']);
                            }
                        } else {
                            array_push($message, ['Reset Password Link Already Used !']);
                        }
                    } else {
                        array_push($message, ['Reset Password Link Expired ']);
                    }
                } else {
                    array_push($message, ['Reset Password Link Is Not Valid ']);
                }
            } else {
                array_push($message, ['Server Error Please Try Later ']);
            }
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
