<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoOrder extends Model
{
    use HasFactory;

    protected $table = 'no_order';

    protected $guarded = []; // can insert all column

    public function unit_usaha()
    {
        return $this->belongsTo('App\Models\UnitUsaha');
    }
}
