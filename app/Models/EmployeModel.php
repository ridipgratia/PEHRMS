<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class EmployeModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'employe';
    protected $table = 'employe';
    protected $fillable = [
        'emp_code',
        'name',
        'email',
        'password',
        'phone',
        'district_code',
        'block_code',
        'gp_code',
        'level_id',
    ];
    protected $hidden = [
        'password'
    ];
}
