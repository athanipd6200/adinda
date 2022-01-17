<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Divisi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_organisasi',
        'id_divisi',
        'nama_divisi',
        'alamat_divisi',
        'logo_divisi',
        'keterangan_divisi',
    ];

    protected $table = 'divisis';
    protected $primaryKey = 'id_entri';

    public function organisasi(){
        return $this->belongsTo(Organisasi::class, 'id_organisasi', 'id_organisasi');
    }

    public function tims(){
        return $this->hasMany(Organisasi::class, 'id_tim', 'id_tim');
    }
}
