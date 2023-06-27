<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\COL_LOG;
use App\Http\Controllers\Controller;


class LogAuditoriaController extends Controller
{
    public function indexLog()
    {
        $logs = COL_LOG::get();
        // dd($logs);
        return view('log_auditoria.index', compact('logs'));
    }
}
