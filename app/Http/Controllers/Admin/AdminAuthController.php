<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
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
    public function login(Request $request)
    {
        $login_data = [
            "email" => $request->email,
            "password" => $request->password
        ];
        $status = null;
        $message = null;
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
                    $token = $user->createToken('AdminToken')->accessToken;
                    $status = 200;
                    return response()->json(['status' => $status, 'token' => $token, 'admin_data' => $user], 200);
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
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    public function profile(Request $request)
    {
        return response()->json(['status' => 200, 'message' => Auth::user()], 200);
    }
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
