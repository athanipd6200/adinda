<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'id_organisasi',
        'id_sub_organisasi'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function keanggotaans(){
        return $this->hasMany(Keanggotaan::class, 'id_user', 'id');
    }

    public function organisasis(){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        $data = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id,'jenis_keanggotaan' => 'organisasi'])->get();
        foreach($data as $key => $value){
            array_push($list_keanggotaan, $value['id_keanggotaan']);
        }
        // error_log(implode(',',$list_keanggotaan));
        return $list_keanggotaan;
    }

    public function divisis(){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        $data = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id,'jenis_keanggotaan' => 'divisi'])->get();
        foreach($data as $key => $value){
            array_push($list_keanggotaan, $value['id_keanggotaan']);
        }
        return $list_keanggotaan;
    }

    public function tims(){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        $data = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id,'jenis_keanggotaan' => 'tim'])->get();
        foreach($data as $key => $value){
            array_push($list_keanggotaan, $value['id_keanggotaan']);
        }
        return $list_keanggotaan;
    }

    public function keanggotaan_by_role(String $role){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        $data = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id, 'role_keanggotaan' => $role])->get();
        foreach($data as $key => $value){
            array_push($list_keanggotaan, $value['id_keanggotaan']);
        }
        return $list_keanggotaan;
    }
}
