<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COL_LOG extends Model
{
    use HasFactory;

    protected $table = 'dbo.COL_LOG';
    public $timestamps = false;
    protected $dateFormat = 'Y-d-m H:i:s';
    protected $primaryKey = 'LOG_ID';

    protected $fillable = [
        'LOG_ID', 
        'FOR_ID', 
        'USUARIO_ID', 
        'EVE_ID', 
        'LOG_DATO_INFO', 
        'LOG_VLR_ANTERIOR', 
        'LOG_VLR_NUEVO', 
        'LOG_OBSERVACIONES', 
        'LOG_FEC_HORA', 
        'LOG_NOMBRE_EQUIPO', 
        'LOG_IP_EQUIPO'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'USUARIO_ID', 'id');
    }

    public function formulario()
    {
        return $this->belongsTo(COL_FORMULARIO::class, 'FOR_ID', 'FOR_ID');
    }

    public function evento()
    {
        return $this->belongsTo(COL_EVENTO::class, 'EVE_ID', 'EVE_ID');
    }
    

}
