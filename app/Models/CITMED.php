<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CITMED extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.CITMED';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = ['CitEmp', 'CitNum'];
    protected $keyType = ['string', 'string'];
    public $incrementing = false;

    protected $fillable = [
        'CitEmp',
        'CitNum',
        'CitEstP'
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
