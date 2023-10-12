@extends('layouts.main', ['activePage' => 'ReportesIndexColsaludInterconsultas', 'titlePage' => 'ReportesIndexColsaludInterconsultas'])
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/ellipsis/0.2.0/js/dataTables.ellipsis.min.js"></script> --}}
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" />


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">FILTROS - INTERCONSULTAS</h4>
                            <p class="card-category"></p>
                        </div>
                        <form action="{{ route('interconsultas.buscar') }}" method="post">
                            @csrf
                            <div class="card-body">

                                <div class="row justify-content-center">
                                    <div class="col-sm-3">
                                        <label for="pabellon">SELECCIONA PABELLON</label>
                                        <select id="pabellon" class="form-control" name="pabellon">
                                            <option value="NULL">Selecciona Pabellon</option>
                                            <option value="9">CUARTO PISO</option>
                                            <option value="46">HEMATO-ONCOLOGIA PEDIATRICA</option>
                                            <option value="22">HOGAR DE PASO</option>
                                            <option value="19">ONCOLOGIA AMBULATORIO</option>
                                            <option value="6">PEDIATRIA</option>
                                            <option value="21">QUINTO PISO</option>
                                            <option value="12">RECUPERACION CIRUGIA</option>
                                            <option value="8">TERCER PISO PABELLON I</option>
                                            <option value="17">TERCER PISO PABELLON II</option>
                                            <option value="7">TOCOFANO</option>
                                            <option value="33">TRANSITORIO UCI ADULTOS</option>
                                            <option value="4">UCI NEONATAL</option>
                                            <option value="5">UCI PEDIATRICA</option>
                                            <option value='1,2,3,15,23,24,25,27,28,29,30,31,32,34'>URGENCIAS</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="estado">SELECCIONA ESTADO</label>
                                        <select id="estado" class="form-control" name="estado">
                                            <option value="NULL">Selecciona Estado</option>
                                            <option value="NULL">TODOS</option>
                                            <option value="A">ATENDIDO</option>
                                            <option value="O">PENDIENTE</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="fecha1">FECHA INICIAL</label>
                                        <input type="date" class="form-control" id="fecha1" name="fecha1" disabled>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="fecha2">FECHA FINAL</label>
                                        <input type="date" class="form-control" id="fecha2" name="fecha2" disabled>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <button type="submit" class="btn btn-primary">CONSULTAR</button>
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">INTERCONSULTAS</h4>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="interconsultas-table" class="table display compact" style="width:100%">
                                            <thead class="text-primary">
                                                <th>Estado</th>
                                                <th>Cama</th>
                                                <th>Tipo ID</th>
                                                <th>Numero Documento</th>
                                                <th>Nombre Paciente</th>
                                                <th>Especialidad</th>
                                                <th>Contrato</th>
                                                <th>Fecha Orden</th>
                                                <th>Fecha Respuesta</th>
                                                <th>Medico Atiende</th>
                                                <th>Pabellon</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($interconsultas as $interconsulta)
                                                    <tr>
                                                        <td>{{ $interconsulta->ESTADO }}</td>
                                                        <td>{{ $interconsulta->CAMA }}</td>
                                                        <td>{{ $interconsulta->TIPO_ID }}</td>
                                                        <td>{{ $interconsulta->NUMERO_ID }}</td>
                                                        <td>{{ $interconsulta->NOMBRE_DE_PACIENTE }}</td>
                                                        <td>{{ $interconsulta->ESPECIALIDAD_SOLICITADA }}</td>
                                                        <td>{{ $interconsulta->CONTRATO }}</td>
                                                        <td>{{ $interconsulta->FECHA_ORDEN }}</td>
                                                        <td>
                                                            <?php if (intval(substr($interconsulta->FECHA_RESPUESTA, 0, 4)) < 2000): ?>
                                                            <!-- si la fecha es anterior al año 2000, dejar vacío -->
                                                            <?php else: ?>
                                                            {{ $interconsulta->FECHA_RESPUESTA }}
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>{{ $interconsulta->NOMBRE_MEDICO_RESPONDE_INTERCONS }}</td>
                                                        <td>{{ $interconsulta->NOMBRE_PABELLON }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        // $(document).ready(function() {
        //     $('#interconsultas-table').DataTable({
        //         "columnDefs": [{
        //             "targets": [4, 5, 6, 7, 8, 9, 10, 11],
        //             "visible": false
        //         }]
        //     });
        // });
        $(document).ready(function() {
            $('#interconsultas-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                }
            });
        });
    </script>
@endsection
