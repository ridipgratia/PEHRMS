<?php

namespace App\MyMethod;

use App\Models\EmployeModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeMethod
{
    public static function checkEmployeData($table, $column_name, $data)
    {
        $check_email = DB::table($table)
            ->where($column_name, $data)
            ->select('id')
            ->get();
        if (count($check_email) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public static function generateEmpCode($level_code)
    {
        $main_text = "PEHRMS";
        // $last_id = DB::table('employe')
        //     ->orderBy('id', 'desc')->first();
        // if ($last_id === null) {
        //     $last_id = 1;
        // }
        $year = date('Y');
        $emp_code = $main_text . $level_code . $year;
        return $emp_code;
    }
    public static function generatePassword()
    {
        $string_upper = "ABCDEFGHIJKLMNOPQRST";
        $string_lower = strtolower($string_upper);
        $number = "1234567890";
        $hash = "@!#$&";
        $password = "";

        for ($j = 0; $j < 2; $j++) {
            $password .= $string_upper[rand(0, strlen($string_upper) - 1)] . $string_lower[rand(0, strlen($string_lower) - 1)] . $number[rand(0, strlen($number) - 1)] . $hash[rand(0, strlen($hash) - 1)];
        }


        return str_shuffle($password);
    }
    // Storage File In Storages
    public static function storeFile($file, $path)
    {
        try {
            $url = $file->store('public/images/' . $path);
            return $url;
        } catch (Exception $err) {
            return NULL;
        }
    }
    public static function deleteFile($file_url)
    {
        try {
            if (Storage::exists($file_url)) {
                Storage::delete($file_url);
            }
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function uploadEmployeFiles($employe_files, $path)
    {
        $check = true;
        $count = 0;
        $url = "";
        foreach ($employe_files as $employe_key => $employe_value) {
            $count++;
            if ($count != 15) {
                $url = EmployeMethod::storeFile($employe_value, '/EmployeDocument/' . $path);
            } else {
                $url = NULL;
            }
            if ($url == NULL) {
                foreach ($employe_files as $delete_key => $delete_value) {
                    $temp_check = EmployeMethod::deleteFile($delete_value);
                }
                Storage::deleteDirectory('public/images/EmployeDocument/' . $path . '/');
                $check = false;
                break;
            } else {
                $employe_files[$employe_key] = $url;
                $check = true;
            }
        }
        return [$check, $employe_files];
    }
    public static function revertEmployeData($table, $colun_name, $id)
    {
        try {
            DB::table($table)
                ->where($colun_name, $id)
                ->delete();
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function uploadFileDatabase($table, $files_data, $id)
    {
        try {
            DB::table($table)
                ->where('id', $id)
                ->update(
                    $files_data
                );
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function uploadFileDatabase_2($table, $files_data, $column_name, $id)
    {
        try {
            DB::table($table)
                ->where($column_name, $id)
                ->update(
                    $files_data
                );
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function revertEmployeFile($employe_files, $path)
    {
        foreach ($employe_files as $file_key => $file_value) {
            EmployeMethod::deleteFile($file_value);
        }
        Storage::deleteDirectory('public/images/EmployeDocument/' . $path . '/');
    }
    // Check Email Registered
    public static function checkEmailRegistered($email)
    {
        $check_email = 0;
        $check = false;
        try {
            $check_email = DB::table('employees')
                ->where('employe_email', $email)
                ->count();
            $check = true;
        } catch (Exception $err) {
            $check = false;
        }
        return [$check, $check_email];
    }
    // Check reset Password Email Already Exists 
    public static function checkResetPasswordEmail($email)
    {
        $check_email = 0;
        $check = false;
        try {
            $check_email = DB::table('change_password_verify')
                ->where('email', $email)
                ->count();
            $check = true;
        } catch (Exception $err) {
            $check = false;
        }
        return [$check, $check_email];
    }
    // Set Reset Password Link In database 
    public static function setResetPasswordData($email, $check_email)
    {
        DB::beginTransaction();
        try {
            $secure_number = rand(100000, 999999);
            $hash_url = Hash::make($email . (string)$secure_number);
            if ($check_email == 1) {
                DB::table('change_password_verify')
                    ->where('email', $email)
                    ->update([
                        'secure_number' => $secure_number,
                        'hash_url' => $hash_url,
                        'expire_time' => date('Y-m-d H:i:s'),
                        'active' => 1
                    ]);
            } else {
                DB::table('change_password_verify')
                    ->insert([
                        'email' => $email,
                        'secure_number' => $secure_number,
                        'hash_url' => $hash_url,
                        'expire_time' => date('Y-m-d H:i:s')
                    ]);
            }
            DB::commit();
            return $hash_url;
        } catch (Exception $err) {
            DB::rollBack();
            return NULL;
        }
    }
    // Check reset Password Valid 
    public static function checkResetPassLinkValid($url)
    {
        $check = false;
        $check_url = 0;
        try {
            $check_url = DB::table('change_password_verify')
                ->where('hash_url', $url)
                ->get();
            $check = true;
        } catch (Exception $err) {
            $check = false;
        }
        return [$check, $check_url];
    }
    // Update reset Password 
    public static function updateResetPassword($url, $email, $password)
    {
        DB::beginTransaction();
        try {
            DB::table('change_password_verify')
                ->where('hash_url', $url)
                ->update([
                    'active' => 2
                ]);
            DB::table('employees')
                ->where('employe_email', $email)
                ->update([
                    'password' => Hash::make($password)
                ]);
            DB::commit();
            return true;
        } catch (Exception $err) {
            DB::rollBack();
            return false;
        }
    }
}
