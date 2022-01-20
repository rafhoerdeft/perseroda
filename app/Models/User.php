<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'nama_user',
    //     'username',
    //     'password',
    // ];

    protected $guarded = []; // can insert all column

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public static function rules($id = null, $merge = [])
    {
        return array_merge(
            [
                'role_id'   => 'required',
                'nama_user' => 'required|max:200',
                'username'  => 'required|min:5|max:100|unique:user,username' . ($id ? ",$id" : ''),
                'password'  => 'required|min:6|max:100',
            ],
            $merge
        );
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
        // return $this->belongsTo('App\Models\Role', 'role_id'); // di identifikasi kolom foreign key jika nama function beda dengan nama tabel join
    }

    public function log_login()
    {
        return $this->hasMany('App\Models\LogLogin');
    }
}
