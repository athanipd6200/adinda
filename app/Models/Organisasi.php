<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_organisasi',
        'nama_organisasi',
        'alamat_organisasi',
        'keterangan_organisasi',
        'logo_organisasi',
        'updated_by',
        'created_by',
    ];

    protected $table = 'organisasis';
    protected $primaryKey = 'id_entri';

    public function divisis(){
        return $this->hasMany(Divisi::class, 'id_organisasi', 'id_organisasi');
    }

    public function tims(){
        return $this->hasMany(Tim::class, 'id_organisasi', 'id_organisasi');
    }
}
