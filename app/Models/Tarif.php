<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarif';

    protected $guarded = []; // can insert all column

    public function rincian_order()
    {
        return $this->hasMany('App\Models\RincianOrder');
    }

    public function barang()
    {
        return $this->belongsTo('App\Models\Barang');
    }

    public function unit_usaha()
    {
        return $this->belongsTo('App\Models\UnitUsaha');
    }
}
