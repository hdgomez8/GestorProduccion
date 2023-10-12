<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ingreso extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.INGRESOS';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = ['MPTDoc', 'MPCedu'];
    protected $keyType = ['string', 'string'];
    public $incrementing = false;

    protected $fillable = [
        'MPTDoc',
        'MPCedu',
        'IngCsc',
        'IngNit',
        'IngFecAdm',
        'IngFecEgr',
        'IngFac'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function getIngresos()
    {
        try {
            DB::enableQueryLog(); // Habilita el registro de consultas

            $sql = "SELECT top 15 ingresos.MPTDoc,ingresos.MPCedu,capbas.MPNOMC,INGRESOS.IngFecAdm,ingresos.IngFac,INGRESOS.IngNit,MAEEMP.MENOMB, INGRESOS.IngCsc FROM INGRESOS 
            join CAPBAS on ingresos.MPTDoc = capbas.MPTDoc and INGRESOS.MPCedu = CAPBAS.MPCedu
            join MAEEMP on ingresos.IngNit = maeemp.MENNIT
            WHERE ingresos.IngFecEgr = '1753-01-01' AND ingresos.IngFecAdm between GETDATE()-10 and GETDATE() order by ingresos.IngFecAdm desc ";

            $consulta = DB::connection('sqlsrv2')->select( $sql);

            $queries = DB::getQueryLog(); // Obtiene las consultas registradas
            Log::info('Consultas ejecutadas:'); // Registro de consultas en el archivo de registro

            foreach ($queries as $query) {
                Log::info($query['query']); // Registra la consulta SQL en el archivo de registro
            }
            
            return $consulta; 
        } catch (\Throwable $th) {
            Log::error('Error al obtener ingresos: ' . $th->getMessage());
            return []; 
        }

    }

    public function capbas()
    {
        return $this->belongsTo(Capbas::class,'MPCedu','MPCedu');
    }

    public function maeemp()
    {
        return $this->hasOne(Maeemp::class,'MENNIT','IngNit');
    }

}
