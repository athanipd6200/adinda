<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artikel extends Model
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
    	'id_artikel',
        'id_keanggotaan',
    	'judul_artikel',
    	'jenis_artikel',
        'teks_pembuka_artikel',
        'gambar_pembuka_artikel',
        'teks_isi_artikel',
        'tanggal_artikel',
        'tags_artikel',
        'geolocation_artikel',
        'lampiran_artikel',
        'penulis_artikel',
        'penyunting_artikel',
        'pengawas_artikel',
        'status_tampilan_artikel',
        'status_verifikasi_artikel',
        'kelompok_artikel',
        'created_by',
        'updated_by',

    ];

    protected $table = 'artikels';
    protected $primaryKey = 'id_entri';
}
