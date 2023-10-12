<?php

use App\Http\Controllers\CarpetaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/despachos', [App\Http\Controllers\FarmaciaController::class, 'index'])->name('despacho.index');
    Route::get('/despachos/ver', [App\Http\Controllers\FarmaciaController::class, 'verDespacho'])->name('despachos.show');
    Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.delete');

    Route::resource('posts', App\Http\Controllers\PostController::class);

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('/compras/enTramite', [App\Http\Controllers\CompraController::class, 'enTramite'])->name('compras.enTramite');
    Route::get('/compras/comprados', [App\Http\Controllers\CompraController::class, 'comprados'])->name('compras.comprados');
    Route::resource('compras', App\Http\Controllers\CompraController::class);
    Route::resource('salidas', App\Http\Controllers\FacturacionController::class);
    Route::resource('carpetas', App\Http\Controllers\CarpetaController::class);
    Route::post('carpetas', [App\Http\Controllers\CarpetaController::class, 'buscarPacienteHosvital'])->name('carpetas.buscarPacienteHosvital');
    Route::post('/carpetas/pacientes', [App\Http\Controllers\CarpetaController::class, 'buscarPacienteCarpetas'])->name('carpetas.buscarPacienteCarpetas');
    Route::post('/carpetas/facturas', [App\Http\Controllers\CarpetaController::class, 'buscarFacturaPaciente'])->name('carpetas.buscarFactura');
    Route::get('/carpetas/{id}/descarga', [App\Http\Controllers\CarpetaController::class, 'DescargarFacturaPaciente'])->name('carpetas.descargarFactura');
    Route::get('/carpetas/pacientes', [App\Http\Controllers\CarpetaController::class, 'pacientes'])->name('carpetas.pacientes');
    Route::get('/carpetas/rips', [App\Http\Controllers\CarpetaController::class, 'rips'])->name('carpetas.rips');
    Route::post('/carpetas/{carpeta}/editado', [App\Http\Controllers\CarpetaController::class, 'editado'])->name('carpetas.editado');
    Route::post("/insertar", [App\Http\Controllers\CarpetaController::class, "insertar"])->name('carpetas.insertar');
    Route::post("/guardar", [App\Http\Controllers\CarpetaController::class, "guardar"])->name('carpetas.guardar');
    Route::delete('/archivos/{id}', [App\Http\Controllers\CarpetaController::class, 'destroyArchivo'])->name('destroyArchivo.delete');
    Route::post("/unirPdf", [App\Http\Controllers\CarpetaController::class, "unirPdf"])->name('carpetas.unirPdf');
    Route::resource('pedidos', App\Http\Controllers\PedidosController::class);
    Route::get('/laboratorio/create', [App\Http\Controllers\UserController::class, 'create'])->name('laboratorio.create');

    Route::get('/test', [App\Http\Controllers\TestController::class, 'test']);
    Route::get('/test2', [App\Http\Controllers\TestController::class, 'test2']);

    // Reportes Uci
    Route::get('/reportes/cuidadocritico/digitalizacion', [App\Http\Controllers\ReportesController::class, 'uciEgresos'])->name('reportes.uciEgresos');
    Route::get('/reportes/cuidadocritico/digitalizacionEgresos', [App\Http\Controllers\ReportesController::class, 'uciEgresosReport'])->name('reportes.uciEgresosReport');

    // Reportes Colsalud
    // Reporte Coosalud
    Route::get('/reportes/colsalud/coosalud/index', [App\Http\Controllers\ReportesController::class, 'index'])->name('coosalud.index');

    // Reporte Interconsulta
    Route::get('/reportes/colsalud/interconsultas/index', [App\Http\Controllers\ReportesController::class, 'indexInterconsultas'])->name('interconsultas.index');
    Route::post('/reportes/colsalud/interconsultas/index', [App\Http\Controllers\ReportesController::class, 'buscarInterconsultas'])->name('interconsultas.buscar');

    // Route::get('/reportes/colsalud/coosalud/subsidiado-AC', [App\Http\Controllers\ReportesController::class, 'subsidiadoAC'])->name('reportes.subsidiadoAC');
    Route::post('/reportes/colsalud/coosalud/subsidiado-AC', [App\Http\Controllers\ReportesController::class, 'subsidiadoAC'])->name('reportes.subsidiadoAC');
    Route::post('/descargar-consultas', [App\Http\Controllers\ReportesController::class, 'descargarTodo'])->name('reportes.descargarTodo');

    //Parametrizaciones Colsalud
    Route::get('/parametrizacion/index', [App\Http\Controllers\ParametrizacionController::class, 'index'])->name('parametrizacion.index');
    Route::get('/filtro', [App\Http\Controllers\ParametrizacionController::class, 'filtro'])->name('filtro');
    Route::get('/filtroP', [App\Http\Controllers\ParametrizacionController::class, 'filtroP'])->name('filtroP');
    Route::get('/obtenerNombreProcedimiento', [App\Http\Controllers\ParametrizacionController::class, 'obtenerNombreProcedimiento'])->name('obtenerNombreProcedimiento');
    Route::get('/obtenerValorVariable', [App\Http\Controllers\ParametrizacionController::class, 'obtenerValorVariable'])->name('obtenerValorVariable');
    Route::post('/actualizarValorVariable', [App\Http\Controllers\ParametrizacionController::class, 'actualizarValorVariable'])->name('actualizarValorVariable');

    //Logs Colsalud
    Route::get('/logs', [App\Http\Controllers\LogAuditoriaController::class, 'indexLog'])->name('log.index');

    // //Consulta Externa Colsalud
    Route::get('/citas_index', [App\Http\Controllers\ConsultaExternaController::class, 'citas_index'])->name('citas_index');
    Route::get('/folio_index', [App\Http\Controllers\ConsultaExternaController::class, 'folio_index'])->name('folio_index');
    Route::get('/filtroNumeroCita', [App\Http\Controllers\ConsultaExternaController::class, 'filtroNumeroCita'])->name('filtroNumeroCita');
    Route::post('/cerrar_folio', [App\Http\Controllers\ConsultaExternaController::class, 'cerrar_folio'])->name('cerrar_folio');

    Route::post('/cambiar_estado_cita', [App\Http\Controllers\ConsultaExternaController::class, 'cambiar_estado_cita'])->name('cambiar_estado_cita');

    // //Estado De La Aplicacion

    Route::get('/app-status', function () {
        $appStatus = [
            'environment' => config('app.env'),
            'version' => config('app.version', 'N/A'),
            'database' => [
                'connection' => DB::connection()->getDriverName(),
                'status' => DB::connection()->getPdo() ? 'Connected' : 'Not Connected',
            ],
            // Agrega más detalles según sea necesario
        ];

        return response()->json($appStatus);
    });
});
