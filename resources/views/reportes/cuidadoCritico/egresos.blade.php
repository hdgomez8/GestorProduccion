
@section('content')
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
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success" role="success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table" id="tablaEgresosUci">
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
@endsection
