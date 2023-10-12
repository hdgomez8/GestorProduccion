<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\Carpetas;
use App\Models\Maeemp;
use App\Models\Archivos;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Karriere\PdfMerge\PdfMerge;
use Illuminate\Support\Facades\DB; // Assuming your model namespace is App\Models
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class CarpetaController extends Controller
{

    //******************************************************************* */
    //*************Visualizar usuarios de hosvital*********************** */
    //******************************************************************* */
    public function index()
    {
        try {
            $carpetas = Ingreso::getIngresos();

            return view('facturacion.carpetas.index', compact('carpetas'));
        } catch (\Exception $e) {
            // Registrar la excepción en los logs
            Log::error($e);
            // Manejar la excepción aquí
            return view('facturacion.error', ['error' => $e->getMessage()]);
        }
    }

    //******************************************************************* */
    //********Verificar si usuario existe en DB o crearlo**************** */
    //******************************************************************* */
    public function insertar(Request $request, Carpetas $carpeta)
    {
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

            // Encontrar el ID del usuario Dueño del archivo
            $idUsuario = DB::table('pacientes')
                ->where('mptdoc', $tipoId)
                ->where('mpcedu', $numeroId)
                ->orderBy('id', 'desc')
                ->value('id');


            // Encontrar la carpeta donde se encuentra el archivo
            $Archivo = DB::table('pacientes')
                ->join('archivos', 'archivos.id_Paciente', '=', 'pacientes.id')
                ->select('pacientes.id', 'archivos.ruta')
                ->where('archivos.nombre_Archivo', '=', 'IDENTIFICACION.pdf')
                ->where('pacientes.mptdoc', 'like', '%' . $tipoId . '%')
                ->where('pacientes.mpcedu', 'like', '%' . $numeroId . '%')
                ->latest('pacientes.id')
                ->first();


            if ($Archivo) {
                // Encontrar la ruta del archivo
                $rutaArchivo = $Archivo->ruta . '\\' . 'IDENTIFICACION.pdf';

                // Copiar el archivo a la nueva carpeta
                $nombreArchivo = 'IDENTIFICACION.pdf';
                //dd($ruta);
                $rutaNueva = $ruta . '\\' . $nombreArchivo;
                copy($rutaArchivo, $rutaNueva);

                //  dd($ruta);
                $documento = new Archivos;
                $documento->usuario = auth()->user()->username;
                $documento->id_Paciente = $idUsuario;
                $documento->ruta = $ruta . '\\';
                $documento->fecha_Guardado = Carbon::now();
                $documento->nombre_Archivo = 'IDENTIFICACION.pdf';
                $documento->save();
            }


            return redirect()->route('carpetas.index')->with('success', 'Usuario Creado Correctamente');
        }
    }

    //******************************************************************* */
    //*************Visualizar usuarios del Gestor******************* */
    //******************************************************************* */
    public function show()
    {
        try {
            $carpetas = Carpetas::orderBy("id", 'DESC')
                ->paginate(15);

            $archivos = Archivos::where("nombre_Archivo", "like", 1 . '%')
                ->paginate(10);

            return view('facturacion.carpetas.show', compact('carpetas', 'archivos'));
        } catch (QueryException $qe) {
            // Manejar excepciones de base de datos
            Log::error($qe);
            return view('facturacion.error', ['error' => 'Error en la base de datos']);
        } catch (\Exception $e) {
            // Otras excepciones generales
            Log::error($e);
            return view('facturacion.error', ['error' => $e->getMessage()]);
        }
    }

    //******************************************************************* */
    //*************Filtrar Pacientes de hosvital************************* */
    //******************************************************************* */
    public function buscarPacienteHosvital(Request $request)
    {
        try {
            $validData = $request->validate([
                'tipoDocumento' => 'required',
                'numeroDocumento' => 'required'
            ]);
    
            $tipoDocumento = trim($request->get('tipoDocumento'));
            $numeroDocumento = trim($request->get('numeroDocumento'));
            $contrato = trim($request->get('contrato'));
    
            $sql = "SELECT ingresos.MPTDoc,ingresos.MPCedu,capbas.MPNOMC,INGRESOS.IngFecAdm,ingresos.IngFac,INGRESOS.IngNit,MAEEMP.MENOMB, INGRESOS.IngCsc 
            FROM INGRESOS 
            join CAPBAS on ingresos.MPTDoc = capbas.MPTDoc and INGRESOS.MPCedu = CAPBAS.MPCedu
            join MAEEMP on ingresos.IngNit = maeemp.MENNIT
            WHERE INGRESOS.MPTDoc = '$tipoDocumento' AND INGRESOS.MPCedu = '$numeroDocumento' order by ingresos.IngFecAdm desc ";
            $carpetas = DB::connection('sqlsrv2')->select($sql);
    
            $contratos = Maeemp::where('MEestado', 0)
                ->where('MEEmpcod', 1)
                ->orderBy("MENOMB", 'asc')
                ->get();
    
            $carpetass = Ingreso::where("INGRESOS.MPTDoc", "like", $tipoDocumento . '%')
                ->where("INGRESOS.MPCedu", "like", $numeroDocumento . '%')
                ->orderBy('INGRESOS.IngCsc', 'DESC')
                ->paginate(30);
    
            return view('facturacion.carpetas.index', compact('carpetas', 'contratos', 'tipoDocumento', 'numeroDocumento'));
        } catch (ValidationException $ve) {
            return view('facturacion.error', ['error' => $ve->getMessage()]);
        } catch (\Exception $e) {
            Log::error($e);
            return view('facturacion.error', ['error' => 'Ocurrió un error inesperado']);
        }

    }

    //******************************************************************* */
    //*************Filtrar Pacientes del Gestor************************** */
    //******************************************************************* */
    public function buscarPacienteCarpetas(Request $request)
    {
        $tipoDocumento = trim($request->get('tipoDocumento'));
        $numeroDocumento = trim($request->get('numeroDocumento'));
        $numeroFactura = trim($request->get('numeroFactura'));
        $numeroRemision = trim($request->get('numeroRemision'));

        $archivos = Archivos::paginate(5);

        $carpetas = Carpetas::orderBy('IngCsc', 'DESC')
            ->tipoDocumento($tipoDocumento)
            ->numeroDocumento($numeroDocumento)
            ->numeroFactura($numeroFactura)
            ->numeroRemision($numeroRemision)
            ->paginate(15);

        return view('facturacion.carpetas.show', compact('carpetas', 'archivos'));
    }


    //******************************************************************* */
    //*************Filtrar Factura del Gestor************************** */
    //******************************************************************* */
    public function buscarFacturaPaciente(Request $request)
    {
        //dd($request);
        $validData = $request->validate([
            'numeroFactura' => 'required'
        ]);

        $numeroFactura = trim($request->get('numeroFactura'));

        $carpetas = Carpetas::paginate(10);

        $archivos = Archivos::where("nombre_Archivo", "like", '%' . $numeroFactura . '%')
            ->orderBy('nombre_Archivo', 'DESC')
            ->paginate(10);

        return view('facturacion.carpetas.show', compact('archivos', 'carpetas'));
    }

    //******************************************************************* */
    //*************Descargar Factura del Gestor************************** */
    //******************************************************************* */
    public function DescargarFacturaPaciente($id)
    {
        // // //dd($id);
        //  $archivos = Archivos::where("id", $id)->firstOrFail();
        //  $pathToFile = ($archivos->ruta . $archivos->nombre_Archivo);
        // // //dd($archivos->nombre_Archivo);
        //  return response()->download($pathToFile);

        try {
            $archivos = Archivos::where("id", $id)->firstOrFail();
            $pathToFile = ($archivos->ruta . $archivos->nombre_Archivo);

            return response()->download($pathToFile);
        } catch (\Exception $e) {
            // Manejo de la excepción
            return back()->withError("No se pudo descargar el archivo. Error: " . $e->getMessage());
        }

        // $archivo = Archivos::find($id);

        // if (!$archivo) {
        //     abort(404, 'Archivo no encontrado.');
        // }

        // $pathToFile = public_path($archivo->ruta . $archivo->nombre_Archivo);

        // if (!is_readable($pathToFile)) {
        //     throw new \Exception('El archivo no se puede leer.');
        // }

        // return Response::download($pathToFile, $archivo->nombre_Archivo);
    }

    //******************************************************************* */
    //*************Editar Pacientes para adjuntar soportes*************** */
    //******************************************************************* */
    public function edit(Carpetas $carpeta)
    {
        try {
            $tipoId = trim($carpeta->MPTDoc);
            $numeroId = trim($carpeta->MPCEDU);
            $eps = trim($carpeta->MENOMB);
            $diaIngresoCarpeta = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $carpeta->IngFecAdm)->toDateString();
            $id = trim($carpeta->id);
    
            $ruta = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId");
            $ruta2 = "$diaIngresoCarpeta\\$eps\\$tipoId$numeroId";
            $rutaArchivoSin = null;
    
            //$archivos = Archivos::get();
    
            $archivos = Archivos::where("id_Paciente", "like",  $id)
                ->orderBy('nombre_Archivo', 'ASC')
                ->paginate(100);
    
            // dd($carpeta);
            return view('facturacion.carpetas.edit', compact('ruta2', 'carpeta', 'ruta', 'archivos', 'rutaArchivoSin'));
        } catch (\Exception $e) {
            // Manejar la excepción aquí
            return view('facturacion.error', ['error' => $e->getMessage()]);
        }

    }

    //******************************************************************* */
    //*************Store*************** */
    //******************************************************************* */
    public function store(Request $request)
    {
        $tipoDocumento = trim($request->get('tipoDocumento'));
        $numeroDocumento = trim($request->get('numeroDocumento'));
        $numeroConsecutivo = trim($request->get('numeroConsecutivo'));

        $IngFac = 0;
        $IngFecAdmI = '01/01/2021';
        $IngFecAdmF = '31/12/2021';

        $carpeta = Ingreso::where("INGRESOS.MPTDoc", "like", '%' . $tipoDocumento . '%')
            ->where("INGRESOS.MPCedu", "like", '%' . $numeroDocumento . '%')
            ->orderBy('INGRESOS.IngCsc', 'DESC')
            ->paginate(10);

        $carpetas = $carpeta->unique("MPCedu");
        return view('facturacion.carpetas.index', compact('carpetas'));
    }

    //******************************************************************* */
    //*************Guardar Documentos Adjuntados*************** */
    //******************************************************************* */
    public function guardar(Carpetas $carpeta, Request $request)
    {
        $id = trim($request->get('id'));
        $tipoId = trim($request->get('TipoIdentificacion'));
        $numeroId = trim($request->get('NumeroIdentificacion'));
        $eps = trim($request->get('Eps'));
        $diaIngresoCarpeta = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->get('Fecha'))->toDateString();
        $nombreDocumento1 = trim($request->get('nombreDocumento1'));
        $nombreDocumento2 = trim($request->get('nombreDocumento2'));
        $nombreDocumento3 = trim($request->get('nombreDocumento3'));
        $nombreDocumento4 = trim($request->get('nombreDocumento4'));

        // Detalles Del Documento Adjunto 1
        $fileType1 = $_FILES['adjunto1']['type'];
        $fileNameCmps1 = explode("/", $fileType1);
        $fileExtension1 = strtolower(end($fileNameCmps1));

        // Detalles Del Documento Adjunto 2
        $fileType2 = $_FILES['adjunto2']['type'];
        $fileNameCmps2 = explode("/", $fileType2);
        $fileExtension2 = strtolower(end($fileNameCmps2));

        // Detalles Del Documento Adjunto 3
        $fileType3 = $_FILES['adjunto3']['type'];
        $fileNameCmps3 = explode("/", $fileType3);
        $fileExtension3 = strtolower(end($fileNameCmps3));

        // Detalles Del Documento Adjunto 4
        $fileType4 = $_FILES['adjunto4']['type'];
        $fileNameCmps4 = explode("/", $fileType4);
        $fileExtension4 = strtolower(end($fileNameCmps4));

        /**Verificamos si existe el directorio Adjunto 1*/
        $nombreCompletoDocumento1 = "$nombreDocumento1.$fileExtension1";
        $ruta1 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\");
        $rutaArchivo1 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\$nombreCompletoDocumento1");
        //dd($rutaArchivo);

        /**Verificamos si existe el directorio Adjunto 2*/
        $nombreCompletoDocumento2 = "$nombreDocumento2.$fileExtension2";
        $ruta2 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\");
        $rutaArchivo2 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\$nombreCompletoDocumento2");
        //dd($rutaArchivo);

        /**Verificamos si existe el directorio Adjunto 3*/
        $nombreCompletoDocumento3 = "$nombreDocumento3.$fileExtension3";
        $ruta3 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\");
        $rutaArchivo3 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\$nombreCompletoDocumento3");
        //dd($rutaArchivo);

        /**Verificamos si existe el directorio Adjunto 4*/
        $nombreCompletoDocumento4 = "$nombreDocumento4.$fileExtension4";
        $ruta4 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\");
        $rutaArchivo4 = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId\\$nombreCompletoDocumento4");
        //dd($rutaArchivo);

        // **************************************************
        // **************************************************
        // ***********Verificar carga de archivos************
        // **************************************************
        // **************************************************

        if ($request->hasFile('adjunto1')) {
            $archivo1 = $request->file('adjunto1');
            if ($request->hasFile('adjunto2')) {
                $archivo2 = $request->file('adjunto2');
                if ($request->hasFile('adjunto3')) {
                    $archivo3 = $request->file('adjunto3');
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            //guardar archivo
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        }
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                            // *******************************Fin Codigo funcional consecutivos*********************************************
                        }
                    } else {
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            //guardar archivo
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        }
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                } else {
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                            // *******************************Fin Codigo funcional consecutivos*********************************************
                        }
                    } else {
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                }
            } else {
                if ($request->hasFile('adjunto3')) {
                    $archivo3 = $request->file('adjunto3');
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                # code...
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    } else {
                        $archivo3 = $request->file('adjunto3');
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                } else {
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                            // *******************************Fin Codigo funcional consecutivos*********************************************
                        }
                    } else {
                        if (file_exists($rutaArchivo1)) {
                            $a = 1;
                            while (file_exists($ruta1 . $nombreDocumento1 . $a . "." . $fileExtension1)) {
                                $a++;
                            }
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . $a . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . $a . "." . $fileExtension1);
                            $reg_Archivo1->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo1 = new Archivos;
                            $reg_Archivo1->usuario = auth()->user()->username;
                            $reg_Archivo1->id_Paciente = $id;
                            $reg_Archivo1->ruta = $ruta1;
                            $reg_Archivo1->fecha_Guardado = Carbon::now();
                            $reg_Archivo1->nombre_Archivo = $nombreDocumento1 . "." . $fileExtension1;
                            $archivo1->move($ruta1, $nombreDocumento1 . "." . $fileExtension1);
                            $reg_Archivo1->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                }
            }
        } else {
            if ($request->hasFile('adjunto2')) {
                $archivo2 = $request->file('adjunto2');
                if ($request->hasFile('adjunto3')) {
                    $archivo3 = $request->file('adjunto3');
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        }
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    } else {
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        }
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                } else {
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    } else {
                        if (file_exists($rutaArchivo2)) {
                            $a = 1;
                            while (file_exists($ruta2 . $nombreDocumento2 . $a . "." . $fileExtension2)) {
                                $a++;
                            }
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2 . $a . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2 . $a . "." . $fileExtension2);
                            $reg_Archivo2->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo2 = new Archivos;
                            $reg_Archivo2->usuario = auth()->user()->username;
                            $reg_Archivo2->id_Paciente = $id;
                            $reg_Archivo2->ruta = $ruta2;
                            $reg_Archivo2->fecha_Guardado = Carbon::now();
                            $reg_Archivo2->nombre_Archivo = $nombreDocumento2  . "." . $fileExtension2;
                            $archivo2->move($ruta2, $nombreDocumento2  . "." . $fileExtension2);
                            $reg_Archivo2->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                }
            } else {
                if ($request->hasFile('adjunto3')) {
                    $archivo3 = $request->file('adjunto3');
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                        }
                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    } else {
                        $archivo3 = $request->file('adjunto3');
                        if (file_exists($rutaArchivo3)) {
                            $a = 1;
                            while (file_exists($ruta3 . $nombreDocumento3 . $a . "." . $fileExtension3)) {
                                $a++;
                            }
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . $a . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3 . $a . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo3 = new Archivos;
                            $reg_Archivo3->usuario = auth()->user()->username;
                            $reg_Archivo3->id_Paciente = $id;
                            $reg_Archivo3->ruta = $ruta3;
                            $reg_Archivo3->fecha_Guardado = Carbon::now();
                            $reg_Archivo3->nombre_Archivo = $nombreDocumento3 . "." . $fileExtension3;
                            $archivo3->move($ruta3, $nombreDocumento3  . "." . $fileExtension3);
                            $reg_Archivo3->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    }
                } else {
                    if ($request->hasFile('adjunto4')) {
                        $archivo4 = $request->file('adjunto4');

                        if (file_exists($rutaArchivo4)) {
                            $a = 1;
                            while (file_exists($ruta4 . $nombreDocumento4 . $a . "." . $fileExtension4)) {
                                $a++;
                            }
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4 . $a . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4 . $a . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        } else {
                            $reg_Archivo4 = new Archivos;
                            $reg_Archivo4->usuario = auth()->user()->username;
                            $reg_Archivo4->id_Paciente = $id;
                            $reg_Archivo4->ruta = $ruta4;
                            $reg_Archivo4->fecha_Guardado = Carbon::now();
                            $reg_Archivo4->nombre_Archivo = $nombreDocumento4  . "." . $fileExtension4;
                            $archivo4->move($ruta4, $nombreDocumento4  . "." . $fileExtension4);
                            $reg_Archivo4->save();
                            $carpetas = Carpetas::get();
                            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
                        }
                    } else {
                        //Queda en la ventana
                        return redirect()->route('carpetas.edit', $id)->with('adjuntar', 'Debes adjuntar Algo');
                    }
                }
            }
        }
    }

    //******************************************************************* */
    //*************Editado*************** */
    //******************************************************************* */
    public function editado(Carpetas $carpeta, Request $request)
    {
        $id = trim($request->get('id'));
        $tipoId = trim($request->get('TipoIdentificacion'));
        $numeroId = trim($request->get('NumeroIdentificacion'));
        $eps = trim($request->get('Eps'));
        $diaIngresoCarpeta = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->get('Fecha'))->toDateString();
        $nombreDocumento = trim($request->get('nombreDocumento'));

        /**Verificamos si existe el directorio */
        $ruta = public_path("Archivos\\$diaIngresoCarpeta\\$eps\\$tipoId$numeroId");
        $ruta2 = "$diaIngresoCarpeta\\$eps\\$tipoId$numeroId";

        if ($request->hasFile('adjunto1')) {
            $archivo = $request->file('adjunto1');
            $archivo->move($ruta, $nombreDocumento . '.pdf');
        }

        $carpeta = Carpetas::get();

        return view('facturacion.carpetas.show', compact('carpeta'));
    }

    //******************************************************************* */
    //************************Unir PDF*********************************** */
    //******************************************************************* */
    public function unirPdf(Request $request)
    {
        //return ($request->ruta);
        $nombresListado = $request->orden;
        $id = $request->id;
        $factura = $request->factura;
        $ruta = $request->ruta;
        

        //dd($tabla);
        //$pdf = new PdfMerge();
        if (file_exists('C:' . $ruta . '\\' . $factura . ".pdf")) {
            $a = 1;
            while (file_exists('C:' . $ruta . '\\' . $factura . $a . ".pdf")) {
                $a++;
            }
            $pdf = new PdfMerge();
            foreach ($nombresListado as $key => $file) {
                $pdf->add($file);
            }
            $pdf->merge('C:' . $ruta  . '\\' . $factura . $a . '.pdf');
            $reg_Archivo = new Archivos;
            $reg_Archivo->usuario = auth()->user()->username;
            $reg_Archivo->id_Paciente = $id;
            $reg_Archivo->ruta = 'C:' . $ruta . '\\';
            $reg_Archivo->fecha_Guardado = Carbon::now();
            $reg_Archivo->nombre_Archivo = $factura . $a . ".pdf";
            $reg_Archivo->save();
            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
        } else {
            $pdf = new PdfMerge();
            foreach ($nombresListado as $key => $file) {
                $pdf->add($file);
            }
            $pdf->merge('C:' . $ruta  . '\\' . $factura . '.pdf');

            $reg_Archivo = new Archivos;
            $reg_Archivo->usuario = auth()->user()->username;
            $reg_Archivo->id_Paciente = $id;
            $reg_Archivo->ruta = 'C:' . $ruta . '\\';
            $reg_Archivo->fecha_Guardado = Carbon::now();
            $reg_Archivo->nombre_Archivo = $factura . ".pdf";
            $reg_Archivo->save();
            return redirect()->route('carpetas.edit', $id)->with('success', 'Documentos Adjuntados Correctamente');
        }
    }

    public function rips()
    {
        dd("Se ha accedido a la vista rips");
    }


    //******************************************************************* */
    //*************Eliminar Registro Del Archivo************************* */
    //******************************************************************* */
    public function destroyArchivo(Request $request)
    {
        $idPaciente = $request->idPaciente;
        $idArchivo = $request->idArchivo;
        $rutaArchivo = $request->rutaArchivo;
        Archivos::destroy($idArchivo);
        File::delete($rutaArchivo);
        //dd($request);
        //echo $id;

        //File::delete();
        //echo $id->nombre_Archivo;
        // abort_if(Gate::denies('user_destroy'), 403);

        // if (auth()->user()->id == $user->id) {
        //     return redirect()->route('users.index');
        // }

        // $user->delete();
        // return back()->with('succes', 'Usuario eliminado correctamente');

        return redirect()->route('carpetas.edit', $idPaciente)->with('destroy', 'Documento Eliminado Correctamente');
    }
}
