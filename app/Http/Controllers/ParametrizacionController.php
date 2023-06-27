<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PORTAR;
use App\Models\COL_FORMULARIO;
use App\Models\COL_EVENTO;
use App\Models\PORTAR1;
use App\Models\MAEPRO;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\HelperController;

class ParametrizacionController extends Controller
{

    //******************************************************************* */
    //***********Visualizar Ventana Parametrizaciones******************** */
    //******************************************************************* */
    public function index()
    {
        return view('parametrizacion.index');
    }

    public function filtro(Request $request)
    {
        $filtro = $request->input('filtro');

        // Realiza la consulta y filtra los datos según el criterio de búsqueda
        $resultados = PORTAR::where('PTCodi', 'LIKE', '%' . $filtro . '%')
            ->limit(10)
            ->get();

        return response()->json($resultados);
    }

    public function filtroP(Request $request)
    {
        $filtro = $request->input('filtro');
        $codigo_portafolio = $request->input('codigo_portafolio');

        // Realiza la consulta y filtra los datos según el criterio de búsqueda

        $resultados = PORTAR1::where('PTCodi', 'LIKE', '%' . $codigo_portafolio . '%')
            ->where('PRCODI', 'LIKE', '%' . $filtro . '%')
            ->limit(10)
            ->get();

        return response()->json($resultados);
    }

    public function obtenerNombreProcedimiento(Request $request)
    {
        $prcodi = $request->input('prcodi');

        // Realiza la consulta y filtra los datos según el criterio de búsqueda

        $resultados = MAEPRO::where('PRCODI', 'LIKE', '%' . $prcodi . '%')
            ->limit(10)
            ->get();

        return response()->json($resultados);
    }

    public function nombresProcedimientos()
    {
        // Realiza la consulta y filtra los datos según el criterio de búsqueda

        $resultados = MAEPRO::get();

        return response()->json($resultados);
    }

    public function obtenerValorVariable(Request $request)
    {
        $codigo_procedimiento = $request->input('codigo_procedimiento');
        $codigo_portafolio = $request->input('codigo_portafolio');

        // Realiza la consulta y filtra los datos según el criterio de búsqueda

        $resultados = PORTAR1::where('PRCODI', 'LIKE', '%' . $codigo_procedimiento . '%')
            ->where('PTCodi', 'LIKE', '%' . $codigo_portafolio . '%')
            ->get();

        return response()->json($resultados);
    }

    public function actualizarValorVariable(Request $request)
    {
        // dd($request);
        $codigo_procedimiento = $request->input('codigo_procedimiento');
        $codigo_portafolio = $request->input('codigo_portafolio');
        $valor_variable_antes = $request->input('valor_variable_antes');
        $actualizar_valor_variable = $request->input('opciones_valorVariable');
        $observacion = $request->input('observacion');

        $nombre_formulario = $request->input('nombre_formulario');
        $id_formulario = COL_FORMULARIO::getIdFormulario($nombre_formulario);
        $accion = $request->input('accion');
        $id_evento = COL_EVENTO::getIdEvento($accion);
        $id_usuario = auth()->user()->id;

        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        // $macAddress = HelperController::obtenerMacEquipo();

        $ipCliente = $_SERVER['REMOTE_ADDR'];

        // Realiza la actualización en la base de datos
        try {
            // Ejecuta la consulta SQL para actualizar el registro

            $sql = "UPDATE PORTAR1 set PTValLib = '$actualizar_valor_variable' where prcodi = '$codigo_procedimiento' and PTCodi = '$codigo_portafolio' ";

            $affectedRows = DB::connection('sqlsrv2')->update($sql);
            // Verifica si se actualizó algún registro
            // Verifica si se actualizó algún registro
            if ($affectedRows > 0) {
                // Actualización exitosa

                $sqlLog = " INSERT INTO COL_LOG ([FOR_ID],[USUARIO_ID], [EVE_ID], [LOG_DATO_INFO], [LOG_VLR_ANTERIOR], [LOG_VLR_NUEVO], [LOG_OBSERVACIONES], [LOG_FEC_HORA], [LOG_NOMBRE_EQUIPO], [LOG_IP_EQUIPO]) 
                VALUES ('$id_formulario','$id_usuario', '$id_evento', 'PORTAFOLIO-$codigo_portafolio/CODIGO-$codigo_procedimiento', 'PTValLib-$valor_variable_antes', 'PTValLib-$actualizar_valor_variable', '$observacion', GETDATE(), '$hostname', '$ipCliente')";
                $Log = DB::connection('sqlsrv')->insert($sqlLog);
                return Redirect::back()->with('success', 'Actualización exitosa');
            } else {
                // No se encontró el registro
                return Redirect::back()->with('error', 'Registro no encontrado');
            }
        } catch (\Exception $e) {
            // Ocurrió un error durante la actualización
            dd($e);
            return response()->json(['error' => 'Error en la actualización'], 500);
        }
    }
}
