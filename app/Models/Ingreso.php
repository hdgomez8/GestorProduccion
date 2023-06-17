<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        $sql = "SELECT top 15 ingresos.MPTDoc,ingresos.MPCedu,capbas.MPNOMC,INGRESOS.IngFecAdm,ingresos.IngFac,INGRESOS.IngNit,MAEEMP.MENOMB, INGRESOS.IngCsc FROM INGRESOS 
        join CAPBAS on ingresos.MPTDoc = capbas.MPTDoc and INGRESOS.MPCedu = CAPBAS.MPCedu
        join MAEEMP on ingresos.IngNit = maeemp.MENNIT
        WHERE ingresos.IngFecEgr = '1753-01-01' AND year(ingresos.IngFecAdm)='2023' order by ingresos.IngFecAdm desc ";
        return $consulta = DB::connection('sqlsrv2')->select( $sql);
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
