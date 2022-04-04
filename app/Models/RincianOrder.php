<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianOrder extends Model
{
    use HasFactory;

    protected $table = 'rincian_order';

    protected $guarded = []; // can insert all column

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function tarif()
    {
        return $this->belongsTo('App\Models\Tarif');
    }
}
