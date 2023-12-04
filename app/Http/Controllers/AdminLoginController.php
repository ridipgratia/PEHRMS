<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function admin_login(Request $request)
    {
        $password = Hash::make('password');
        DB::table('admin')
            ->insert([
                'admin_code' => 'admin123',
                'email' => 'admin@gmail.com',
                'password' => $password,
                'role' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        return response()->json(['status' => 200, 'message' => 'Ok'], 200);
    }
}
