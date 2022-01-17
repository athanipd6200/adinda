<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tim extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_tim',
        'id_organisasi',
        'id_divisi',
        'nama_tim',
        'alamat_tim',
        'logo_tim',
        'keterangan_tim',
    ];

    protected $table = 'tims';
    protected $primaryKey = 'id_entri';

    public function organisasi(){
        return $this->belongsTo(Organisasi::class, 'id_organisasi', 'id_organisasi');
    }

    public function divisi(){
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }
}
