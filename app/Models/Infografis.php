<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infografis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'source',
        'inserted_by',
        'description',
        'index_infografis',
        'tags_infografis',
        'tautan_infografis',
        'tampilan_web',
    ];

    protected $table = 'infografis';
    protected $primaryKey = 'id_entri';
}
