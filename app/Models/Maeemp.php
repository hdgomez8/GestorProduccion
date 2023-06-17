<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maeemp extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.MAEEMP';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = 'MENNIT';
    protected $keyType = 'string';

    protected $fillable = [
        'MENNIT',
        'MENOMB',
        'MEestado',
        'MEEmpcod'
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

    function ingreso()
    {
        return $this->belongsTo(Ingreso::class,'IngNit','MENNIT');
    }
}
