<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class COL_FORMULARIO extends Model
{
    use HasFactory;

    protected $table = 'dbo.COL_FORMULARIO';
    public $timestamps = false;
    protected $dateFormat = 'Y-d-m H:i:s';
    protected $primaryKey = 'FOR_ID';
    //protected $dateFormat = 'Y-d-m H:i:s.u'; // o el formato que te sirva

    protected $fillable = [
        'FOR_ID',
        'MOD_ID',
        'FOR_NOMBRE',
        'FOR_DESCRIPCION',
        'FOR_DATO',
        'FOR_RUTA'
    ];


    public static function getIdFormulario($nombre_formulario)
    {
        $consulta = DB::connection('sqlsrv')
            ->table('COL_FORMULARIO')
            ->select('FOR_ID')
            ->where('FOR_NOMBRE', $nombre_formulario)
            ->first();

        if ($consulta) {
            return $consulta->FOR_ID;
        }

        return null;
    }
}
