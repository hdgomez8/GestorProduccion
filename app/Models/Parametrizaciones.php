<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametrizaciones extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.CAPBAS';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
    protected $primaryKey = ['MPTDoc', 'MPCedu'];
    protected $keyType = ['string', 'string'];
    public $incrementing = false;

    protected $fillable = [
        'MPTDoc',
        'MPCedu',
        'MPNOMC'
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

    public function ingreso() {
        return $this->hasMany(Ingreso::class,['MPCedu', 'MPTDoc'], ['MPCedu', 'MPTDoc']);
    }

     public function ultimoIngreso(){
         return $this->hasOne(Ingreso::class,'MPCedu')->orderBy("IngFecAdm","DESC");
     }

}
