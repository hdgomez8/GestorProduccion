<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturacion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalidasRequest;
use Carbon\Carbon;

class FacturacionController extends Controller
{
    public function index()
    {
        $salidas = Facturacion::where("HISCKEY", "like", 1002069837)
            ->where("HISTIPDOC", "like", '%CC%')
            // ->where("HISFECSAL", "<>", '01/01/1753')
            ->orderBy('HISFECSAL', 'desc')
            ->take(10)
            ->get();

        return view('facturacion.salidas.index', compact('salidas'));
    }

    public function store(SalidasRequest $request)
    {
        $tipoDocumento = trim($request->get('tipoDocumento'));
        $numeroDocumento = trim($request->get('numeroDocumento'));
        $numeroConsecutivo = trim($request->get('numeroConsecutivo'));
        $fechaInicial = trim($request->get('fechaInicial'));
        $fechaFinal = trim($request->get('fechaFinal'));

        // $fechaI = $request->fechaInicial;
        // $fechaF = $request->fechaFinal;

        // dd($fechaInicial,$fechaFinal);


        $salidas = Facturacion::where("HISTIPDOC", "like", '%' . $tipoDocumento . '%')
            ->where("HISCKEY", "like", '%' . $numeroDocumento . '%')
            ->where("HCTVIN1", "like", '%' . $numeroConsecutivo . '%')
            ->where("HISFECSAL", "<>", '01/01/1753')
            // ->where('HISFECSAL', '>=', $fechaI)
            // ->whereBetween('HISFECSAL', [$fechaInicial, $fechaFinal])
            ->take(1)
            ->get();
        // ->orderBy('HISFECSAL','DESC')
        // ->orderBy("HISCSEC", 'DESC')
        //->paginate(10);            

        // dd($salidas);

        return view('facturacion.salidas.index', compact('salidas', 'numeroDocumento', 'tipoDocumento', 'numeroConsecutivo'));
    }

    public function edit(Request $salida)
    {
        //dd($salida);
        return view('facturacion.salidas.edit', compact('salida'));
    }
}
