@extends('layouts.main', ['activePage' => 'facturacionVerPacientes', 'titlePage' => 'Ver Pacientes'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Adjuntar Soportes</h4>
                                </div>

                                <form action="{{ route('carpetas.buscarPacienteCarpetas') }}" method="post">
                                    @csrf
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success" role="success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="row justify-content-center">

                                            <label for="name" class="col-sm-2 col-form-label">Tipo Documento</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="tipoDocumento">
                                                    <option value=" ">Ingrese Tipo Documento</option>
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
                                                    placeholder="Ingrese Numero Documento" value="{{old('numeroDocumento')}}" autofocus>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center my-1">
                                            <div class="col-2 text-right">
                                                <input type="submit" value="BUSCAR" class="btn btn-sm"
                                                    style="background:#0EA0A7; color:white;">
                                            </div>
                                        </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-primary text-center">
                                            <th>Tipo Documento</th>
                                            <th>Numero Documento</th>
                                            <th>Nombre Paciente</th>
                                            <th>Nombre Empresa</th>
                                            <th>Fecha Admision</th>
                                            <th>Consecutivo De Ingreso</th>
                                            <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($carpetas as $carpeta)
                                                <tr>
                                                    <td>{{ $carpeta->MPTDoc ?? null }}</td>
                                                    <td>{{ $carpeta->MPCEDU ?? null }}</td>
                                                    <td>{{ $carpeta->MPNOMC ?? 'None' }}</td>
                                                    <td>{{ $carpeta->MENOMB ?? 'None' }}</td>
                                                    <td>{{ $carpeta->IngFecAdm ?? null }}</td>
                                                    <td>{{ $carpeta->IngCsc ?? null }}</td>
                                                    <td class="td-actions text-right">
                                                        <div class="btn-group">
                                                            @can('facturacion_soportes_ver')
                                                                <a href="{{ route('carpetas.edit', $carpeta) }}"
                                                                    class="btn btn-success"><i
                                                                        class="material-icons">visibility</i>Ver</a>
                                                            @endcan
                                                            @can('facturacion_soportes_adjuntar')
                                                                <a href="{{ route('carpetas.edit', $carpeta) }}"
                                                                    class="btn btn-warning"><i
                                                                        class="material-icons">attach_file</i>Adjuntar</a>
                                                            @endcan
                                                        </div>
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
@endsection
