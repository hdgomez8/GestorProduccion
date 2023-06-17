<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(){
        $tipoId = trim($request->get('TipoIdentificacion'));
        $numeroId = trim($request->get('NumeroIdentificacion'));
        $nombre = trim($request->get('NombrePaciente'));
        $contratoID = trim($request->get('ContratoId'));
        $eps = trim($request->get('Eps'));
        $CscId = trim($request->get('ConsecutivoId'));
        $diaIngreso = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->get('Fecha'));
        $diaIngresoCarpeta = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->get('Fecha'))->toDateString();
        $factura = trim($request->get('NumeroFactura'));

        if ($paciente = Carpetas::where("MPTDoc", $tipoId)
            ->where("MPCedu", $numeroId)
            ->where("MPNOMC", $nombre)
            ->where("MENNIT", $contratoID)
            ->where("MENOMB", $eps)
            ->where("IngCsc", $CscId)
            ->where("IngFac", $factura)
            ->exists()
        ) {
            return redirect()->route('carpetas.index')->with('success', 'Usuario Ya Existe');
        } else {

            /**Verificamos si existe el directorio */
            $ruta = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId");

            //isDirectory () tomará un argumento como ruta de carpeta y devolverá true si la carpeta existe o false.
            if (!File::isDirectory($ruta)) {
                //makeDirectory () tomará cuatro argumentos para crear una carpeta con permiso
                File::makeDirectory($ruta, 0777, true, true);
            }

            //******************************************************************* */
            //*************Guardar datos en base de datos************************ */
            //******************************************************************* */

            $carpeta = new Carpetas();
            $carpeta->MPTDoc = $tipoId;
            $carpeta->MPCedu = $numeroId;
            $carpeta->MPNOMC = $nombre;
            $carpeta->MENNIT = $contratoID;
            $carpeta->MENOMB = $eps;
            $carpeta->IngCsc = $CscId;
            $carpeta->IngFecAdm = $diaIngreso;
            $carpeta->IngFac = $factura;

            $carpeta->save();

            // Encontrar la carpeta donde se encuentra el archivo
            $Archivo = DB::table('pacientes')
            ->join('archivos', 'archivos.id_Paciente', '=', 'pacientes.id')
            ->select('archivos.ruta')
            ->where('archivos.nombre_Archivo', '=', 'IDENTIFICACION.PDF')
            ->where('pacientes.mptdoc', '=', $tipoId)
            ->where('pacientes.mpcedu', '=', $numeroId)
            ->latest('pacientes.id')
            ->first();

            if ($Archivo) {
                // Encontrar la ruta del archivo
                $rutaArchivo = public_path($archivo->ruta);
            
                // Copiar el archivo a la nueva carpeta
                $nombreArchivo = basename($rutaArchivo);
                $rutaNueva = $ruta . '\\' . $nombreArchivo;
                File::copy($rutaArchivo, $rutaNueva);
            }
            

            return redirect()->route('carpetas.index')->with('success', 'Usuario Creado Correctamente');
    }

    public function test2()
    {
        $sql = "SELECT top 15 ingresos.MPTDoc,ingresos.MPCedu,capbas.MPNOMC,INGRESOS.IngFecAdm,ingresos.IngFac,INGRESOS.IngNit,MAEEMP.MENOMB, INGRESOS.IngCsc FROM INGRESOS 
        join CAPBAS on ingresos.MPTDoc = capbas.MPTDoc and INGRESOS.MPCedu = CAPBAS.MPCedu
        join MAEEMP on ingresos.IngNit = maeemp.MENNIT
        WHERE ingresos.IngFecEgr = '1753-01-01' AND year(ingresos.IngFecAdm)='2023' order by ingresos.IngFecAdm desc ";
        $consulta = DB::connection('sqlsrv2')->select( $sql);
        dd($consulta);
   
    }

}
