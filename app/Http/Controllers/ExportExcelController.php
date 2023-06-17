<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\excel;
use App\Exports\UsersExport;

class ExportExcelController extends Controller
{
    public function export() 
    {
        return excel::download(new UsersExport, 'users.xlsx');
    }
}