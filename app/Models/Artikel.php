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
        'verification_artikel',
        'verificator_artikel',
        'email_creator_artikel',
        'organization_creator_artikel',
        'sub_organization_creator_artikel',
        'tampilan_web',
        'penyunting_artikel',
    ];

    protected $table = 'artikels';
    protected $primaryKey = 'id_entri';
}
