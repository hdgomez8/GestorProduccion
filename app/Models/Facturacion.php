<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.HCCOM1';
    protected $primaryKey = 'HISCKEY';
    protected $dateFormat = 'M j Y h:i:s';
    public $timestamps = false;
    protected $dates = ['HISFECSAL'];
    

    protected $fillable = [
        'HISCKEY',
        'HISTipDoc',
        'HISCSEC', 
        'HISFECSAL', 
        'HCtvIn1'
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
