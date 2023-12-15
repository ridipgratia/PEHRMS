<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeBankModel extends Model
{
    use HasFactory;
    protected $table = 'employe_bank_details';
    protected $fillable = [
        'employe_id',
        'account_number',
        'account_name',
        'ifsc_code',
        'bank_name',
        'branch_name',
    ];
}
