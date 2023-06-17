<?php

namespace App\Http\Controllers;

use App\Models\Capbas;
use Illuminate\Http\Request;
use App\Models\Despachos;


class FarmaciaController extends Controller
{
    //index, create, store, show, edit, update y destroy
    public function index()
    {
        $despachos = Despachos::where('DsCnsDsp', '<>', 0)
        ->whereYear('DSmFHrMov', '2022')  
        ->orderBy('DsCnsDsp', 'desc')
        ->paginate(20);

        $despachos = $despachos->unique('HISCKEY');

        //dd($despachos);
        $despachosNombre = [];

        foreach ($despachos as $despacho) {
            $busqueda = Capbas::where('MPTDoc', '=', $despacho->HISTipDoc)
                ->where('MPCedu', '=', $despacho->HISCKEY)
                ->first();

            //dd($busqueda->MPNOMC);
            $despachosNombre[] = [
                'HISTipDoc' => $despacho->HISTipDoc,
                'HISCKEY' => $despacho->HISCKEY,
                'MPNOMC' => $busqueda->MPNOMC ?? "",
                'DsCnsDsp' => $despacho->DsCnsDsp
            ];
        }
        //dd($despachosNombre);
        return view('farmacia.despachos.index', ['despachos' => $despachosNombre]);
    }

    public function verDespacho(Request $request){
         //dd($request);
         //$numeroDespacho = trim($despacho['DsCnsDsp']);
         dd($request);
        $despacho = Despachos::where('DsCnsDsp', '=', $numeroDespacho)
        ->whereYear('DSmFHrMov', '2022')  
        ->orderBy('DsCnsDsp', 'desc')
        ->paginate(20);

        //$despachos = $despachos->unique('HISCKEY');

        //dd($despachos);
        return view('farmacia.despachos.show', compact('despacho'));
    }
}
