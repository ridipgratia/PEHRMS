<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeServiceModel extends Model
{
    use HasFactory;
    protected $table = 'employe_service_record';
    protected $fillable = [
        'employe_id',
        'promoted_to_curr_des',
        'promoted_from_curr_des',

        'bdo_status', // Whether In charge BDO/ GP secretary (select in charge BDO status
        'transferred_from_district',
        'transferred_from_block',
        'transferred_from_gp',
        'transferred_to_district',
        'transferred_to_block',
        'transferred_to_gp',
        'transferred_document',
        'transferred_date',
        'previous_joining_document',
        'previous_joining_date',
        'service_branch',
    ];
}
