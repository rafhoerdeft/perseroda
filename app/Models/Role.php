<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $guarded = []; // can insert all column


    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
