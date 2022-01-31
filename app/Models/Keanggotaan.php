<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keanggotaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_user',
        'id_keanggotaan',
        'jenis_keanggotaan',
        'role_keanggotaan',
        'permissions_keanggotaan',
        'created_by',
        'updated_by',
    ];

    protected $table = 'keanggotaans';
    protected $primaryKey = 'id_entri';

    public function users(){
        return $this->hasMany(User::class, 'id', 'id_user');
    }

}
