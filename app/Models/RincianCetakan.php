<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianCetakan extends Model
{
    use HasFactory;

    protected $table = 'rincian_cetakan';

    protected $guarded = []; // can insert all column

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
