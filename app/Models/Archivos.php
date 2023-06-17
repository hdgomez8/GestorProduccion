<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivos extends Model
{
    //use HasFactory;
    protected $table="archivos";

    public $timestamps = false;

    protected $fillable = [
        'id_Paciente',
        'usuario',
        'ruta',
        'nombre_Archivo',
        'estado_Archivo',
        'fecha_Guardado'
    ];

    public function paciente()
    {
        return $this->belongsTo(Carpetas::class,'id_Paciente');
    }
}

