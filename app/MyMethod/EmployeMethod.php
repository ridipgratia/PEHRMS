<?php

namespace App\MyMethod;

use App\Models\EmployeModel;
use Exception;
use Illuminate\Support\Facades\DB;
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
    public static function uploadEmployeFiles($employe_files)
    {
        $check = false;
        foreach ($employe_files as $employe_key => $employe_value) {
            $check_url = EmployeMethod::storeFile($employe_value, 'HRMS123');
            if ($check_url == NULL) {
                foreach ($employe_files as $delete_key => $delete_value) {
                    $temp_check = EmployeMethod::deleteFile($delete_value);
                }
                $check = false;
                break;
            } else {
                $employe_files[$employe_key] = $check_url;
                $check = true;
            }
        }
        return [$check, $employe_files];
    }
}
