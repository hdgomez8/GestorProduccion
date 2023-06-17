<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAEPRO extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.MAEPRO';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = ['PRCODI'];
    protected $keyType = ['string'];
    public $incrementing = false;

    protected $fillable = [
        'PRCODI',
        'PrNomb'
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
