<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Boolean;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
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
        $organisasis = $this->organisasis();
        $divisi_hereditary = Divisi::select('id_divisi')->whereIn('id_organisasi', $organisasis)->get();
        // anggota penunjukan langsung
        $divisi_assigned = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id,'jenis_keanggotaan' => 'divisi'])->get();
        foreach($divisi_hereditary as $key => $value){
            array_push($list_keanggotaan, $value['id_divisi']);
        }
        foreach($divisi_assigned as $key => $value){
            array_push($list_keanggotaan, $value['id_keanggotaan']);
        }
        return $list_keanggotaan;
    }

    public function tims(){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        // daftar organisasi yang ditanggungi
        $organisasis = $this->organisasis();
        // tim turunan dari organisasi
        $tim__organisasi_hereditary = Tim::select('id_tim')->whereIn('id_organisasi', $organisasis)->get();
        foreach($tim__organisasi_hereditary as $key => $value){
            array_push($list_keanggotaan, $value['id_tim']);
        }
        // daftar divisi yang ditanggungi
        $organisasis = $this->divisis();
        // tim turunan dari divisi
        $tim__divisi_hereditary = Tim::select('id_tim')->whereIn('id_divisi', $organisasis)->get();
        foreach($tim__divisi_hereditary as $key => $value){
            array_push($list_keanggotaan, $value['id_tim']);
        }
        // mengambil id tim dari tim yang ditugaskan
        $tim_assigned = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id,'jenis_keanggotaan' => 'tim'])->get();
        foreach($tim_assigned as $key => $value){
            array_push($list_keanggotaan, $value['id_keanggotaan']);
        }
        // error_log(implode(',',$list_keanggotaan));
        return $list_keanggotaan;
    }

    public function keanggotaan_by_role(String $role, $detail = false){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        $data = [];
        if($this->hasRole('SuperAdmin') && $detail == false){
            $list_keanggotaan = $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->get();
            return $list_keanggotaan;
            
        }else{
            $data = $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->get();
            foreach($data as $key => $value){
                array_push($list_keanggotaan, $value['id_keanggotaan']);
            }
            return $list_keanggotaan;
        }
        
        if($detail == false){
            if($role == 'all'){
                $data = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id])->get();
            }else{
                $roles = explode('&',$role);
                $data = Keanggotaan::select('id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
            }
            foreach($data as $key => $value){
                array_push($list_keanggotaan, $value['id_keanggotaan']);
            }
            // $list_keanggotaan = array_unique($list_keanggotaan);
        }else{
            if($role == 'all'){
                $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where(['id_user' => $id])->get();
                // $data = Keanggotaan::all();
            }else{
                $roles = explode('&',$role);
                $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
            }
            // foreach($data as $key => $value){
            //     array_push($list_keanggotaan, $value['id_keanggotaan']);
            // }
            $list_keanggotaan = $data;
        }
        return $list_keanggotaan;
    }

    public function keanggotaan_by_roles(Array $roles, $detail = false){
        $id = $this->attributes['id'];
        $list_keanggotaan = [];
        $data = [];
        if(count($roles) == 0){
            return $list_keanggotaan;
        }
        if($detail == false){
            foreach($roles as $key1 => $role){
                $id_kegiatans = $this->keanggotaan_by_role($role);
                foreach($id_kegiatans as $key2 => $id_kegiatan_value){
                    array_push($list_keanggotaan, $id_kegiatan_value);
                }
            }
            $list_keanggotaan = array_unique($list_keanggotaan);
        }else{
            foreach($roles as $key1 => $role){
                $id_kegiatans = $this->keanggotaan_by_role($role, true);
                foreach($id_kegiatans as $key2 => $value){
                    array_push($list_keanggotaan, $value);
                }
            }
        }
        return $list_keanggotaan;
    }

}
