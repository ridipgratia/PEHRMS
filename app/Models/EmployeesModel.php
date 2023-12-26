<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class EmployeesModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'employe';
    protected $table = 'employees';
    protected $fillable = [
        'level_id',
        'employe_code',
        'password',
        'employe_name',
        'employe_designation',
        'service_status',
        'employe_phone',
        'employe_category',
        'employe_alt_number',
        'employe_email',
        'employe_profile',
        'employe_father_name',
        'employe_mother_name',
        'employe_dob',
        'employe_birth_certificate',
        'pan_number',
        'aadhar_number',
        'gender',
        'nationality',
        'personal_marks_of_identification',
        'caste',
        'race',
        'pwd_document',
        'posted_district',
        'posted_block',
        'posted_gp',
        'date_of_order',
        'order_document',
        'date_of_joining',
        'current_joining_document',
        'branch',
        'initial_date_of_joining',
        'initial_appointment_letter',
        'initial_joining_letter',
        'state',
        'district',
        'block',
        'gp',
        'current_address',
        'permanent_address'

    ];
    protected $hidden = [
        'password'
    ];
}
