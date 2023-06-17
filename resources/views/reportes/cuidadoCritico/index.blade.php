@extends('layouts.main', ['activePage' => 'ReportesIndexUci', 'titlePage' => 'ReportesIndexUci'])
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" />

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Reportes</h4>
                                    <p class="card-category">Reportes Cuidado Critico</p>
                                </div>

                                <div class="card-body" id="tablaEgreso">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="spinner text-center" id="onload">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div>
                                            <p>Cargando</p>
                                        </div>
                                    </div>
                                    <div class="table-responsive hidden" id="tablaEgresosUciContainer">
                                        <table class="table " id="tablaEgresosUci">
                                            <thead class="text-primary">
                                                <th>numero_de_comprobante</th>
                                                <th>valor_de_giro</th>
                                                <th>numero_de_cheque</th>
                                                <th>pago_a_favor_de_</th>
                                                <th>beneficiario</th>
                                                <th>formato</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($documentos as $documento)
                                                    <tr>
                                                        <td>{{ $documento->numero_de_comprobante }}</td>
                                                        <td>{{ $documento->valor_de_giro }}</td>
                                                        <td>{{ $documento->numero_de_cheque }}</td>
                                                        <td>{{ $documento->pago_a_favor_de_ }}</td>
                                                        <td>{{ $documento->beneficiario }}</td>
                                                        <td>{{ $documento->formato }}</td>
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
@endsection

@section('js')
    <script src="//code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <script>
        $(window).on('load', function() {
            $('#tablaEgreso').show();
            console.log('finish');
        });
        $(document).ready(function() {
            console.log('init');
            $('#tablaEgreso').hide();
            $('#onload').fadeOut();
            $('#tablaEgresosUci').DataTable({
                responsive: true,
                autoWidth: false,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

        });
    </script>
@endsection
