<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'clave', 'nombre',
    ];

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class);
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }
}

