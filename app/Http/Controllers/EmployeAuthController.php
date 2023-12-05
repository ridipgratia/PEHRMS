<?php

namespace App\Http\Controllers;

use App\Models\EmployeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeAuthController extends Controller
{
    public function register(Request $request)
    {
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
    public function profile(Request $request)
    {
        return response()->json(['employe_data' => Auth::user()], 200);
    }
    public function logout(Request $request)
    {
        if (auth()->user()) {
            Auth::user()->tokens->each(function ($token) {
                $token->revoke();
            });
        }
        return response()->json(['message' => 'Logout User '], 200);
    }
}
