<?php

namespace App\MyMethod;

use Exception;
use Illuminate\Support\Facades\DB;

class AdminMethod
{
    public static function getAllDistricts()
    {
        try {
            $districts = DB::table('districts')
                ->orderBy('district_name', 'asc')
                ->select('district_code', 'district_name')
                ->get();
            return $districts;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function getAllBlocks($district_code)
    {
        try {
            $blocks = DB::table('blocks')
                ->where('district_id', $district_code)
                ->select('block_id', 'block_name')
                ->orderBy('block_name', 'asc')
                ->get();
            return $blocks;
        } catch (Exception $err) {
            return false;
        }
    }

    public static function getAllGPs($block_code)
    {

        try {
            $gram_panchayats = DB::table('gram_panchyats')
                ->where('block_id', $block_code)
                ->select('gram_panchyat_id', 'gram_panchyat_name')
                ->orderBy('gram_panchyat_name', 'asc')
                ->get();
            return $gram_panchayats;
        } catch (Exception $err) {
            return false;
        }
    }
}
