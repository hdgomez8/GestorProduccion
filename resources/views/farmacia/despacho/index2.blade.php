@extends('layouts.main', ['activePage' => 'laboratorioPedidos', 'titlePage' => 'Pedidos De Laboratorio'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Pedidos</h4>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table display compact" style="width:100%">
                                            <thead class="text-primary">
                                                <th>Tipo Identificacion</th>
                                                <th>Numero Documento</th>
                                                <th>Nombre Paciente</th>
                                                <th>Numero Despacho</th>
                                                <th>Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($despachos as $despacho)
                                                    <tr>
                                                        <td>{{ $despacho['HISTipDoc'] }}</td>
                                                        <td>{{ $despacho['HISCKEY'] }}</td>
                                                        <td>{{ $despacho['MPNOMC'] }}</td>
                                                        <td>{{ $despacho['DsCnsDsp'] }}</td>
                                                        <td class="td-actions text-right">
                                                            <div class="btn-group">
                                                                {{-- <a href="{{ route('farmacia.despacho.show', $despacho['DsCnsDsp']) }}"
                                                                    class="btn btn-success"><i
                                                                        class="material-icons">visibility</i>Descargar</a> --}}
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!--Ventana Modal para Actualizar--->
                                                    @include('farmacia/despacho/modal/ModalEditar')
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
