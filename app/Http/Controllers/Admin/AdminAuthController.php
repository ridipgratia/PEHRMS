<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    // Admin Register
    public function register(Request $request)
    {
        $admin = AdminModel::create([
            'admin_code' => $request->admin_code,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        $token = $admin->createToken('AdminToken')->accessToken;
        return response()->json(['token' => $token, 'admin' => $admin], 200);
    }

    // Admin Login
    public function login(Request $request)
    {
        $login_data = [
            "email" => $request->email,
            "password" => $request->password
        ];
        $status = null;
        $message = null;
        $error_message = [
            'required' => 'Fill All Credentials !'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ],
            $error_message
        );
        if ($validator->fails()) {
            $status = 400;
            $message = $error_message['required'];
        } else {
            if (Auth::guard('admin')->attempt($login_data)) {
                $check = false;
                try {
                    $login_user = AdminModel::where('email', $request->email)->first();
                    $check = true;
                } catch (Exception $err) {
                    $check = false;
                }
                if ($check) {
                    if ($login_user) {
                        Auth::login($login_user);
                        $user = Auth::user();
                        $token = $login_user->createToken('AdminToken')->accessToken;
                        $status = 200;
                        return response()->json(['status' => $status, 'token' => $token], 200);
                    } else {
                        $status = 400;
                        $message = "Admin Email Not Found !";
                    }
                } else {
                    $status = 400;
                    $message = "Try Later Database Error ";
                }
            } else {
                $status = 400;
                $message = "Admin Crdentials Not Found !";
            }
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }

    // Admin Profile
    public function profile(Request $request)
    {
        return response()->json(['status' => 200, 'data' => Auth::user()], 200);
    }

    // Admin Logout
    public function logout(Request $request)
    {
        if (auth()->user()) {
            Auth::user()->tokens->each(function ($token) {
                $token->revoke();
            });
        }
        return response()->json(['status' => 200, 'message' => 'Logout Out'], 200);
    }
}
