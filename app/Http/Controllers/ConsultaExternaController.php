<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\COL_FORMULARIO;
use App\Models\COL_EVENTO;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\HelperController;
use App\Models\CITMED;
use Illuminate\Support\Facades\Validator;

class ConsultaExternaController extends Controller
{

    //******************************************************************* */
    //***********Visualizar Ventana Citas******************** */
    //******************************************************************* */
    public function citas_index()
    {
        return view('consulta_externa.citas_index');
    }

    //******************************************************************* */
    //***********Visualizar Ventana Folios******************** */
    //******************************************************************* */
    public function folio_index()
    {
        return view('consulta_externa.folio_index');
    }

    //******************************************************************* */
    //***********Visualizar Ventana Folios******************** */
    //******************************************************************* */
    public function cerrar_folio(Request $request)
    {

        // Realiza la actualización en la base de datos
        try {
            // dd($request);

            $messages = [
                'tipoDocumento.required' => 'El Tipo de Documento es obligatorio.',
                'tipoDocumento.required' => 'El Numero de Documento es obligatorio.',
                'tipoDocumento.not_in' => 'Seleccione tipo documento.',
                // Agrega aquí los mensajes personalizados para los demás campos
            ];

            $validator = Validator::make($request->all(), [
                'tipoDocumento' => 'required|not_in:X',
                'numeroDocumento' => 'required',
                // Agrega aquí las reglas de validación para los demás campos
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $tipoDocumento = trim($request->get('tipoDocumento'));
            $numeroDocumento = trim($request->get('numeroDocumento'));

            $nombre_formulario = $request->input('nombre_formulario');
            $id_formulario = COL_FORMULARIO::getIdFormulario($nombre_formulario);
            $id_usuario = auth()->user()->id;
            $accion = $request->input('accion');
            $id_evento = COL_EVENTO::getIdEvento($accion);

            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            // $macAddress = HelperController::obtenerMacEquipo();
            $ipCliente = $_SERVER['REMOTE_ADDR'];

            // Ejecuta la consulta SQL para actualizar el registro
            $sqlVerificarDatos = "SELECT * from HCCOM1 where HISTipDoc = '$tipoDocumento' and hisckey = '$numeroDocumento'";
            $affectedRowsConsulta = DB::connection('sqlsrv2')->select($sqlVerificarDatos);

            $sqlVerificarSiExistenFoliosAbiertos = "SELECT * from HCCOM1 where HISTipDoc = '$tipoDocumento' and hisckey = '$numeroDocumento' and HISCCIE='0' ";
            $affectedRowsConsultaAbiertos = DB::connection('sqlsrv2')->select($sqlVerificarSiExistenFoliosAbiertos);

            if (empty($affectedRowsConsultaAbiertos)) {
                return redirect()->back()->withErrors(['error' => 'No existen folios abiertos de ese paciente.'])->withInput();
            }

            if (empty($affectedRowsConsulta)) {
                return redirect()->back()->withErrors(['error' => 'No se encontraron resultados para la busqueda.'])->withInput();
            }

            // Ejecuta la consulta SQL para actualizar el registro
            $sql = "UPDATE HCCOM1 set HISCCIE = '1' where HISTipDoc = '$tipoDocumento' and hisckey = '$numeroDocumento' and HISCCIE='0' ";
            $affectedRows = DB::connection('sqlsrv2')->update($sql);
            // Verifica si se actualizó algún registro
            if ($affectedRows > 0) {
                // Actualización exitosa

                $sqlLog = " INSERT INTO COL_LOG ([FOR_ID],[USUARIO_ID], [EVE_ID], [LOG_DATO_INFO], [LOG_VLR_ANTERIOR], [LOG_VLR_NUEVO], [LOG_FEC_HORA], [LOG_NOMBRE_EQUIPO], [LOG_IP_EQUIPO]) 
                VALUES ('$id_formulario','$id_usuario', '$id_evento', 'TIPO_DOCUMENTO-$tipoDocumento/NUMERO_DOCUMENTO-$numeroDocumento', 'HISCCIE-0', 'HISCCIE-1', GETDATE(), '$hostname', '$ipCliente')";
                $Log = DB::connection('sqlsrv')->insert($sqlLog);
                return Redirect::back()->with('success', 'Folio Cerrado Correctamente');
            } else {
                // No se encontró el registro
                return Redirect::back()->with('error', 'Registro no encontrado');
            }
        } catch (\Exception $e) {
            // Ocurrió un error durante la actualización
            // dd($e);
            return response()->json(['error' => 'Error en la actualización'], 500);
        }

        return view('consulta_externa.folio_index');
    }

    //******************************************************************* */
    //***********Filtro Numero Cita******************** */
    //******************************************************************* */
    public function filtroNumeroCita(Request $request)
    {
        $filtro = $request->input('filtro');

        // Realiza la consulta y filtra los datos según el criterio de búsqueda
        $resultados = CITMED::where('CitEmp', '01')
            ->where('CitNum', $filtro)
            ->whereIn('CitEstP', ['A', 'C', 'R'])
            ->get();

        return response()->json($resultados);
    }

    //******************************************************************* */
    //***********Cambiar Estado Cita******************** */
    //******************************************************************* */
    public function cambiar_estado_cita(Request $request)
    {
        // dd($request);
        // Realiza la actualización en la base de datos
        try {

            $messages = [
                'observacion.required' => 'El Tipo de Documento es obligatorio.',
                'observacion.min' => 'La observación debe tener al menos 10 caracteres.',
                'actualizar_estado_cita.not_in' => 'Seleccione estado.',
            ];

            $validator = Validator::make($request->all(), [
                'observacion' => ['required', 'string', 'min:10', 'max:100'],
                // Agrega aquí las reglas de validación para los demás campos
                'actualizar_estado_cita' => 'required|not_in:X',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $numero_cita = trim($request->get('numero_cita'));
            $valor_cita_antes = trim($request->get('valor_cita_antes'));
            $actualizar_estado_cita = trim($request->get('actualizar_estado_cita'));
            $observacion = trim($request->get('observacion'));

            $nombre_formulario = $request->input('nombre_formulario');
            $id_formulario = COL_FORMULARIO::getIdFormulario($nombre_formulario);

            $id_usuario = auth()->user()->id;

            $accion = $request->input('accion');
            $id_evento = COL_EVENTO::getIdEvento($accion);

            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            // $macAddress = HelperController::obtenerMacEquipo();
            $ipCliente = $_SERVER['REMOTE_ADDR'];

            // Ejecuta la consulta SQL para actualizar el registro
            $sql1 = "UPDATE CITMED set CitEstP='$actualizar_estado_cita' where CitNum = '$numero_cita' and CitEmp='01'";
            $sql2 = "UPDATE CITMED1 set CitEsta='$actualizar_estado_cita' where CitNum = '$numero_cita' and CitEmp='01'";

            $affectedRows1 = DB::connection('sqlsrv2')->update($sql1);
            $affectedRows2 = DB::connection('sqlsrv2')->update($sql2);
            // Verifica si se actualizó algún registro
            if ($affectedRows1 > 0) {
                // Actualización exitosa
                $sqlLog = " INSERT INTO COL_LOG ([FOR_ID],[USUARIO_ID], [EVE_ID], [LOG_DATO_INFO], [LOG_VLR_ANTERIOR], [LOG_VLR_NUEVO], [LOG_OBSERVACIONES], [LOG_FEC_HORA], [LOG_NOMBRE_EQUIPO], [LOG_IP_EQUIPO]) 
                VALUES ('$id_formulario','$id_usuario', '$id_evento', 'NUMERO_CITA-$numero_cita', 'CitEstP-$valor_cita_antes', 'CitEstP-$actualizar_estado_cita', '$observacion', GETDATE(), '$hostname', '$ipCliente')";
                $Log = DB::connection('sqlsrv')->insert($sqlLog);
                return Redirect::back()->with('success', 'Cambio de estado exitoso');
            } else {
                // No se encontró el registro
                return Redirect::back()->with('error', 'Registro no encontrado');
            }
        } catch (\Exception $e) {
            // Ocurrió un error durante la actualización
            // dd($e);
            return response()->json(['error' => 'Error en la actualización'], 500);
        }

        return view('consulta_externa.citas_index');
    }
}
