<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitUsaha extends Model
{
    use HasFactory;

    protected $table = 'unit_usaha';

    protected $guarded = []; // can insert all column

    public function tarif()
    {
        return $this->hasMany('App\Models\Tarif');
    }

    public function no_order()
    {
        return $this->hasOne('App\Models\NoOrder');
    }
}
