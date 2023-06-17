<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;


class UsuarioController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('compra_index'), 403);
        $usuarios = Usuario::where("AGrpId","like",'%ENFERMERIA%')
        // ->orwhere("MatQxAdq","like",'%EN TRAMITE%')
        ->paginate(200);
        // ->where("MatQxAdq","like","%COMPRADO%");
        // dd($compras);
        // ->paginate(5)
        // return view('compras.index', compact('usuarios'));

    }
}
