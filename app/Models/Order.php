<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order';

    protected $guarded = []; // can insert all column
    protected $dates = ['deleted_at'];

    public function rincian_order()
    {
        return $this->hasMany('App\Models\RincianOrder');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function unit_usaha()
    {
        return $this->belongsTo('App\Models\UnitUsaha');
    }
}
