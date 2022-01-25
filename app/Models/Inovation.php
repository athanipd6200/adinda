<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inovation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_inovation',
        'id_keanggotaan',
        'nama_inovation',
        'ikon_inovation',
        'gambar_inovation',
        'satker_asal_inovation',
        'kontak_hubung_inovation',
        'deskripsi_inovation',
        'konten_inovation',
        'tautan_materi_inovation',
        'tautan_kode_inovation',
        'status_tampilan_inovation',
        'status_verifikasi_inovation',
        'created_by',
        'updated_by',
        'verified_by',
        'supervised_by',
    ];

    protected $table = 'inovations';
    protected $primaryKey = 'id_entri';
}
