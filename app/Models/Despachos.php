<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despachos extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.DSPFRMC1';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = 'DsNumDoc';
    protected $keyType = 'string';

    protected $fillable = [
        'HISTipDoc',
        'HISCKEY',
        'DsCnsDsp',
        'DSmFHrMov'
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
