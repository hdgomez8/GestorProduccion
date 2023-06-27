<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class COL_EVENTO extends Model
{
    use HasFactory;

    protected $table = 'dbo.COL_EVENTO';
    public $timestamps = false;
    protected $dateFormat = 'Y-d-m H:i:s';
    protected $primaryKey = 'EVE_ID';
    //protected $dateFormat = 'Y-d-m H:i:s.u'; // o el formato que te sirva

    protected $fillable = [
        'EVE_ID',
        'EVE_NOMBRE',
        'EVE_DESCRIPCION'
    ];


    public static function getIdEvento($accion)
    {
        $consulta = DB::connection('sqlsrv')
            ->table('COL_EVENTO')
            ->select('EVE_ID')
            ->where('EVE_NOMBRE', $accion)
            ->first();

        if ($consulta) {
            return $consulta->EVE_ID;
        }
        return null;
    }
}
