<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'producto',
        'cantidad',
        'total',
        'estado',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
