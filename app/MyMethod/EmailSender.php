<?php

namespace App\MyMethod;

use App\Mail\MailNotify;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailSender
{
    public static function emailSend($data, $email, $email_blade)
    {
        // $data = ['name' => 'coder 1', 'data' => 'developer'];
        $check = false;
        try {
            // $employe['to'] = $email;
            // Mail::send($email_blade, $data, function ($messages) use ($employe, $data) {
            //     $messages->to($employe['to']);
            //     $messages->subject($data['subject']);
            // });
            $data['email_blade'] = $email_blade;
            Mail::to($email)->send(new MailNotify($data));
            $check = true;
        } catch (Exception $err) {
            $check = false;
        }
        return $check;
    }
    public static function saveOTP($table, $data)
    {
        $check = false;
        try {
            $check_email = DB::table($table)
                ->where('email', $data['email'])
                ->select('id')
                ->get();
            if (count($check_email) == 0) {
                DB::table($table)
                    ->insert($data);
                $check = true;
            } else {
                DB::table($table)
                    ->where('email', $data['email'])
                    ->update($data);
                $check = true;
            }
        } catch (Exception $err) {
            $check = false;
        }
        return $check;
    }
}
