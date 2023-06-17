<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Carpetas extends Model
{
    use HasFactory;

    protected $table = 'dbo.pacientes';
    public $timestamps = false;
    protected $dateFormat = 'Y-d-m H:i:s';
    //protected $dateFormat = 'Y-d-m H:i:s.u'; // o el formato que te sirva

    protected $fillable = [
        // 'AUsrId',
        'MPTDoc',
        'MPCEDU',
        'MPNOMC',
        'MENNIT',
        'MENOMB',
        'IngCsc',
        'IngFac',
        'REMISION',
    ];

    protected $dates = [
        'IngFecAdm',
    ];

    public function archivos()
    {
        return $this->hasMany(Archivos::class);
    }

    //Query Scope
    public function scopetipoDocumento($query, $tipoDocumento)
    {
        if($tipoDocumento)
            return $query->where('MPTDoc','LIKE',"$tipoDocumento%");
    }

    public function scopenumeroDocumento($query, $numeroDocumento)
    {
        if($numeroDocumento)
            return $query->where('MPCEDU','LIKE',"$numeroDocumento%");
    }

    public function scopenumeroFactura($query, $numeroFactura)
    {
        if($numeroFactura)
            return $query->orWhere('IngFac','LIKE',"$numeroFactura%");
    }

    public function scopenumeroRemision($query, $numeroRemision)
    {
        if($numeroRemision)
            return $query->orWhere('REMISION','LIKE',"$numeroRemision%");
    }
}
