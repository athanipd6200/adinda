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

        // dd($detail);

        // if($this->hasRole('SuperAdmin') && $detail == false){
        //     $list_keanggotaan = $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->get();
        //     return $list_keanggotaan;
            
        // }else{
        //     $data = $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->get();
        //     foreach($data as $key => $value){
        //         array_push($list_keanggotaan, $value['id_keanggotaan']);
        //     }
        //     return $list_keanggotaan;
        // }
        
        if($detail == false){
            if($role == 'all'){
                $data = Keanggotaan::select('id_keanggotaan')->where(['id_user' => $id])->get();
            }else{
                // dd($id);
                $roles = explode('&',$role);
                $data = Keanggotaan::select('id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
                // $data = Keanggotaan::select('id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->toSql();
                // dd($data);

            }
            foreach($data as $key => $value){
                array_push($list_keanggotaan, $value['id_keanggotaan']);
            }
            // $list_keanggotaan = array_unique($list_keanggotaan);
        }else{
            
            if($role == 'all'){
                $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where(['id_user' => $id])->get();
                // $flag = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where(['id_user' => $id]);

                
                // $data = Keanggotaan::all();
            }else{
                $childRoles = array();

                $roles = explode('&',$role);
                // dd($id);

                $organisasiRoles = Keanggotaan::join('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->count();
                $divisiRoles = Keanggotaan::join('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->count();
                $timRoles = Keanggotaan::join('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->count();
                // dd($organisasiRoles);    

                if($organisasiRoles>0){
                    $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
                    // $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->toSql();
                    // dd($data);

                    array_push($childRoles,$data);
                }elseif($divisiRoles>0){
                    // dd("disini");
                    $data = Keanggotaan::leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_divisi', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
                    array_push($childRoles,$data);

                }elseif($timRoles){
                    $data = Keanggotaan::leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
                    array_push($childRoles,$data);

                }



                
                // $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_divisi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_tim', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();

                // dd($data);
            }
            // foreach($data as $key => $value){
            //     array_push($list_keanggotaan, $value['id_keanggotaan']);
            // }
            $list_keanggotaan = $childRoles;
        }
        // dd($list_keanggotaan);
        return $list_keanggotaan;
    }

    // public function checkParent($role)
    // {
    //     $id = $this->attributes['id'];
    //     $roles = explode('&',$role);
    //     $data = Keanggotaan::distinct('jenis_keanggotaan')->where('id_user', $id)->whereIn('role_keanggotaan', $roles)->get();
    //     return $data;
    // }

    public function getChild($id_kegiatan_value)
    {
        $id = $this->attributes['id'];
        // dd($id_kegiatan_value);
        // $data = Keanggotaan::leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->where(['id_user' => $id],['id_keanggotaan' => $id_kegiatan_value])->get();
        $data = Keanggotaan::select('id_keanggotaan','divisis.id_divisi','tims.id_tim')->leftJoin('organisasis', 'organisasis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('divisis', 'divisis.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->leftJoin('tims', 'tims.id_organisasi', '=', 'keanggotaans.id_keanggotaan')->where('id_user', $id)->where('id_keanggotaan', $id_kegiatan_value)->get();
        // dd($data);
        return $data;

        # code...
    }

    // public function getLevel($array1, $array2)
    // {
    //     $count = 0;
    //     foreach ($array1 as $i) {
    //         if (in_array($i,$array2)) $count++;
    //     }

    //     return $count;
    //     # code...
    // }

    public function keanggotaan_by_roles(Array $roles, $detail = false){
        $id = $this->attributes['id'];
        $akses = ['organisasi', 'divisi', 'tim'];
        $list_keanggotaan = [];
        $list_access = [];
        $list_divisi = [];
        $list_tim = [];
        $data = [];
        if(count($roles) == 0){
            return $list_keanggotaan;
        }
        // dd($roles[0]);
        if($detail == false){
            foreach($roles as $key1 => $role){
                // dd($role);
                $id_kegiatans = $this->keanggotaan_by_role($role);
                foreach($id_kegiatans as $key2 => $id_kegiatan_value){
                    $childs = $this->getChild($id_kegiatan_value);

                    foreach ($childs as $child){
                        if($child->id_divisi != null){
                            array_push($list_divisi, $child->id_divisi);
                            array_push($list_keanggotaan,$child->id_divisi);

                        }

                        if($child->id_tim != null){
                            array_push($list_tim, $child->id_tim);
                            array_push($list_keanggotaan,$child->id_tim);

                        }
                    }

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
        // dd($list_keanggotaan);
        return $list_keanggotaan;
    }

}
