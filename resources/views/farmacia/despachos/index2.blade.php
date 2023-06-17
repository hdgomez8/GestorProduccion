@extends('layouts.main', ['activePage' => 'facturacionCarpetas', 'titlePage' => 'Carpetas'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Pacientes Admitidos</h4>
                                </div>

                                <form action="{{ route('carpetas.buscarPacienteHosvital') }}" method="post">
                                    @csrf
                                    <div class="card-body">

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
                                                                    <form action="{{ route('despachos.show') }}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        <div class="btn-group">
                                                                            <input type="hidden" class="form-control"
                                                                                name="numeroDespacho" @php
                                                                                    $numeroDespacho = trim($despacho['DsCnsDsp']);
                                                                                @endphp
                                                                                value="{{ $numeroDespacho ?? 'None' }}"
                                                                                placeholder="{{ $numeroDespacho ?? 'None' }}"
                                                                                readonly>
                                                                            {{-- <a href="{{ route('despachos.show', $numeroDespacho) }}"
                                                                                class="btn btn-success"><i
                                                                                    class="material-icons">visibility</i>Ver
                                                                                Despacho</a> --}}
                                                                        </div>
                                                                        <div class="d-flex justify-content-center">
                                                                            @csrf
                                    
                                                                            <input type="submit" value="Ver Despacho" class="btn btn-lg btn-success">
                                                                        </div>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
