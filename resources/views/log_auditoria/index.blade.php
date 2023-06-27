@extends('layouts.main', ['activePage' => 'logs', 'titlePage' => 'logs'])
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/ellipsis/0.2.0/js/dataTables.ellipsis.min.js"></script> --}}
<link href="{{ asset('css/estilos.css') }}" rel="stylesheet" />
<style>
    .header-cell {
        background-color: #ebebeb; /* Color de fondo */
        color: #333; /* Color del texto */
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Auditoria</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="logs-table" class="table display compact" style="width:100%">
                                <thead class="text-primary">
                                    <th class="header-cell">Formulario</th>
                                    <th class="header-cell">Usuario</th>
                                    <th class="header-cell">Evento</th>
                                    <th class="header-cell">Informacion</th>
                                    <th class="header-cell">Valor Anterior</th>
                                    <th class="header-cell">Valor Nuevo</th>
                                    <th class="header-cell">Observaciones</th>
                                    <th class="header-cell">Fecha</th>
                                    <th class="header-cell">Nombre Equipo</th>
                                    <th class="header-cell">Ip Equipo</th>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->formulario->FOR_NOMBRE }}</td>
                                        <td>{{ $log->usuario->name }}</td>
                                        <td>{{ isset($log->EVE_ID) ? $log->evento->EVE_NOMBRE : '' }}</td>
                                        <td>{{ $log->LOG_DATO_INFO }}</td>
                                        <td>{{ $log->LOG_VLR_ANTERIOR }}</td>
                                        <td>{{ $log->LOG_VLR_NUEVO }}</td>
                                        <td>{{ $log->LOG_OBSERVACIONES }}</td>
                                        <td>{{ $log->LOG_FEC_HORA }}</td>
                                        <td>{{ $log->LOG_NOMBRE_EQUIPO }}</td>
                                        <td>{{ $log->LOG_IP_EQUIPO }}</td>
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


@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#logs-table').DataTable({
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