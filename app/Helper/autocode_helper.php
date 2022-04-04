<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('auto_code')) {
    function auto_code($select = '', $table = '', $kode_awal = '', $jml_kode = '')
    {
        $row = DB::table($table)->select($select)->latest('id')->first();
        if ($row) { // Check data
            $kode = substr($row->$select, strlen($kode_awal), $jml_kode); // Mengambil string beberapa digit
            $code = (int) $kode; // Mengubah String jadi Integer
            $code++;
            $auto_code = $kode_awal . str_pad($code, $jml_kode, "0", STR_PAD_LEFT); // Kerangka Kode Otomatis = kode_pasar + 6 digit
        } else {
            $code = '';
            for ($i = 1; $i < $jml_kode; $i++) {
                $code .= '0';
            }
            $auto_code = $kode_awal . $code . '1';
        }

        return $auto_code;
    }
}
