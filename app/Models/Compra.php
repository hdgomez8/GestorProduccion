<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    
    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.ReporteTableroQX';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = 'ProCirCod';

    protected $fillable = [
        'ProCirCod',
        'MPTDoc',
        'MPNOMC',
        'MPCedu',
        'Edad',
        'MPSexo',
        'EmpDsc',
        'MMNomM',
        'ProFSep',
        'ProEsta',
        'AutEstado',
        'ProReMaE',
        'ProObMaE',
        'MatQxAdq',
        'ObsMatQx',
        'ProDispEE',
        'ProObsEE',
        'ProResCam',
        'ProTipCam',
        'ProObRCam',
        'ProReqHD',
        'ProObsHD',
        'ProRValAn',
        'TFCCODCAM',
        'MPNomP',
        'crgcod',
        'prnomb',
        'ResCama',
        'ObsResCama',
        'ResHemDer',
        'ObsResHem',
        'FchCompTra',
        'FchCompra',
        'FchRexCam',
        'FchhemRes',
        'autservcsc',
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
}
