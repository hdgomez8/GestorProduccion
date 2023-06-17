<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Maeate extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv2';
    protected $table = 'dbo.MAEATE';
    public $timestamps = false;
    protected $dateFormat = 'M j Y h:i:s';
}