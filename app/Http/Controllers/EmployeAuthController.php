<?php

namespace App\Http\Controllers;

use App\Models\EmployeModel;
use App\MyMethod\EmailSender;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeAuthController extends Controller
{
    public function register(Request $request)
    {
        // Employe Registration 
        $employe = EmployeModel::create([
            'emp_code' => $request->emp_code,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'district_code' => $request->district_code,
            'block_code' => $request->block_code,
            'gp_code' => $request->gp_code,
            'level_id' => $request->level_id,
        ]);
        $token = $employe->createToken('EmployeToken')->accessToken;
        return response()->json(['token' => $token, 'employe' => $employe], 200);
    }
    // Employe Login
    public function login(Request $request)
    {
        $login_data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::guard('employe')->attempt($login_data)) {
            $login_user = EmployeModel::where('email', $request->email)->first();
            Auth::login($login_user);
            $employe = Auth::user();
            $token = $employe->createToken('EmployeToken')->accessToken;
            return response()->json(['token' => $token, 'employe_data' => $employe], 200);
        } else {
            return response()->json(['message' => 'User Not Found '], 400);
        }
    }
    // Employe Profile
    public function profile(Request $request)
    {
        return response()->json(['employe_data' => Auth::user()], 200);
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
                $expire_time = $login_employe->created_at;
                $send_time_stamp = new DateTime($send_time);
                $diff = $send_time_stamp->diff(new DateTime($expire_time));
                $diff = [$diff->d, $diff->h, $diff->i];
                // $check = EmailSender::saveOTP('employe_lotp');
                // EmailSender::emailSend($emailData, $login_employe->email, 'employe_otp');
                $status = 200;
                return response()->json(['status' => $status, 'email' => $diff], 200);
            } else {
                $message = "Email ID Not Found !";
            }
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
}
