<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'barang';

    protected $guarded = []; // can insert all column
    protected $dates = ['deleted_at'];

    public function tarif()
    {
        return $this->hasOne('App\Models\Tarif');
    }
}
