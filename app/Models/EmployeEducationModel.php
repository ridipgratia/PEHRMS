<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeEducationModel extends Model
{
    use HasFactory;
    protected $table = 'employe_education_details';
    protected $fillable = [
        'employe_id',
        'employe_degree',
        'board_name',
        'marks',
        'percentage',
        'passing_year',
    ];
}
