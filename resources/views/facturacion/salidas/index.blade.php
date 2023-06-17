@extends('layouts.main', ['activePage' => 'facturacionSalidas', 'titlePage' => 'Salidas'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Salidas</h4>
                                </div>

                                <form action="{{ route('salidas.store') }}" method="post">
                                    @csrf
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success" role="success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        <div class="row justify-content-center">
                                            <label for="tipoDocumento" class="col-sm-2 col-form-label">Tipo
                                                Documento</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="tipoDocumento">
                                                    <option selected>Ingrese Tipo Documento</option>
                                                    <option value="AS">ADULTO SIN IDENTIFICAR</option>
                                                    <option value="CD">CARNET DIPLOMATICO</option>
                                                    <option value="CC">CEDULA DE CIUDADANIA</option>
                                                    <option value="CE">CEDULA DE EXTRANJERIA</option>
                                                    <option value="CN">CERTIFICADO DE NACIDO VIVO</option>
                                                    <option value="MS">MENOR SIN IDENTIFICAR</option>
                                                    <option value="NI">NIT</option>
                                                    <option value="NUI">NUMERO UNICO DE IDENTIFICACION</option>
                                                    <option value="PA">PASAPORTE</option>
                                                    <option value="PE">PERMISO ESPECIAL DE PERMANENCIA</option>
                                                    <option value="PT">PERMISO POR PROTECCION TEMPORAL</option>
                                                    <option value="RC">REGISTRO CIVIL</option>
                                                    <option value="RE">RESIDENTE ESPECIAL PARA LA PAZ</option>
                                                    <option value="SC">SALVO CONDUCTO</option>
                                                    <option value="TI">TARJETA DE IDENTIDAD</option>
                                                </select>
                                            </div>
                                            <label for="name" class="col-sm-2 col-form-label">Numero Documento</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="numeroDocumento"
                                                    placeholder="Ingrese Numero Documento"
                                                    value="{{ old('numeroDocumento') }}" autofocus>
                                            </div>

                                        </div>

                                        <div class="row my-1 border-0">
                                            <label for="name" class="col-sm-2 col-form-label">Numero Consecutivo</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" name="numeroConsecutivo"
                                                    placeholder="Ingrese Consecutivo"
                                                    value="{{ old('numeroConsecutivo') }}" autofocus>
                                            </div>
                                            <label for="name" class="col-sm-2 col-form-label">Fecha Inicial</label>
                                            <div class="col-sm-2">
                                                <input type="date" class="form-control" name="fechaInicial"
                                                    value={{ old('fechaInicial') }}>
                                            </div>
                                            <label for="name" class="col-sm-2 col-form-label">Fecha Final</label>
                                            <div class="col-sm-2">
                                                <input type="date" class="form-control" name="fechaFinal"
                                                    value={{ old('fechaFinal') }}>
                                            </div>
                                            <div class="col-7 text-right">
                                                @can('compra_pendientes')
                                                    <input type="submit" value="BUSCAR" class="btn btn-sm"
                                                        style="background:#0EA0A7; color:white;">
                                                @endcan
                                            </div>
                                        </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-primary">
                                            <th>Tipo Documento</th>
                                            <th>Numero Documento</th>
                                            <th>Fecha Salida</th>
                                            <th>Consecutivo Salida</th>
                                            <th class="text-right">Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($salidas as $salida)
                                                <tr>
                                                    <td>{{ $salida->HISTipDoc }}</td>
                                                    <td>{{ $salida->HISCKEY }}</td>
                                                    <td>{{ $salida->HISFECSAL->format('Y-m-d h:i:s') }}</td>
                                                    <td>{{ $salida->HCtvIn1 }}</td>
                                                    <td class="td-actions text-right">
                                                        @can('compra_show')
                                                            <a href="{{ route('salidas.edit', $salida->HISCSEC) }}"
                                                                class="btn btn-info"><i
                                                                    class="material-icons">event_available</i></a>
                                                        @endcan

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Compras</h4>
                                    {{-- <p class="card-category">Compras Pendientes Por Realizar</p> --}}
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            @can('compra_pendientes')
                                                <a href="#" class="btn btn-sm" style="background:#F0A693;">Pendientes</a>
                                            @endcan
                                            @can('compra_enTramite')
                                                <a href="#" class="btn btn-sm" style="background:#FFFFAD;">En Tramite</a>
                                            @endcan
                                            @can('compra_compradas')
                                                <a href="#" class="btn btn-sm" style="background:#BDECB6;">Comprados</a>
                                            @endcan
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table display compact" id="tablaUsuarios" style="width:100%">
                                            <thead class="text-primary">
                                                <th>Tipo Documento</th>
                                                <th>Numero Documento</th>
                                                <th>Fecha Salida</th>
                                                <th>Consecutivo Salida</th>
                                                <th class="text-right">Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($salidas as $salida)
                                                    <tr>
                                                        <td>{{ $salida->HISTipDoc }}</td>
                                                        <td>{{ $salida->HISCKEY }}</td>
                                                        <td>{{ $salida->HISFECSAL}}</td>
                                                        <td>{{ $salida->HCtvIn1 }}</td>
                                                        <td class="td-actions text-right">
                                                            @can('compra_show')
                                                                <a href="{{ route('salidas.edit', $salida->HISCSEC) }}"
                                                                    class="btn btn-info"><i
                                                                        class="material-icons">event_available</i></a>
                                                            @endcan
    
                                                        </td>
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
