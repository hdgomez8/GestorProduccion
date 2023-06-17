<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORTAR extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.PORTAR';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = ['PTCodi'];
    protected $keyType = ['string'];
    public $incrementing = false;

    protected $fillable = [
        'PTCodi',
        'PTDesc'
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

    // function ingreso() {
    //     return $this->belongsTo(Ingreso::class, 'MPCedu');
    // }

}
