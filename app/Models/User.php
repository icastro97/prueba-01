<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "usuarios";

    protected $fillable = [
        'nombre',
        'correo_electronico',
        'id_rol',
        'fecha_ingreso',
        'firma',
        'contrato'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }
}
