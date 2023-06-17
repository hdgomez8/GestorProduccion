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
                                        <div class="row justify-content-start">
                                            <label for="name" class="col-sm-2 col-form-label">Tipo Documento</label>
                                            <div class="col-sm-3">
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

                                            <label for="name" class="col-sm-3 col-form-label">Numero Documento</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="numeroDocumento"
                                                    placeholder="Ingrese Numero Documento" autofocus>
                                            </div>


                                        </div>

                                        <div class="row justify-content-center my-1">
                                            <div class="col-2 text-right">
                                                <input type="submit" value="BUSCAR" class="btn btn-sm"
                                                    style="background:#0EA0A7; color:white;">
                                            </div>
                                        </div>
                                </form>

                                <div>
                                    @foreach ($carpetas as $carpeta)
                                        <hr>
                                        <form action="{{ route('carpetas.insertar') }}" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="form-row ">
                                                <div class="form-group col-md-2">
                                                    <label>Tipo Identificacion</label>
                                                    <input type="text" class="form-control" name="TipoIdentificacion"
                                                        @php $tipoIdentificacion = trim($carpeta->MPTDoc); @endphp
                                                        value="{{ $tipoIdentificacion ?? 'None' }}"
                                                        placeholder="{{ $tipoIdentificacion ?? 'None' }}" readonly>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Numero Identificacion</label>
                                                    <input type="text" class="form-control" name="NumeroIdentificacion"
                                                        @php $numeroIdentificacion = trim($carpeta->MPCedu); @endphp
                                                        value="{{ $numeroIdentificacion ?? 'None' }}"
                                                        placeholder="{{ $numeroIdentificacion ?? 'None' }}" readonly>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label>Nombre Paciente</label>
                                                    <input type="text" class="form-control" name="NombrePaciente"
                                                        @php $nombre = trim($carpeta->MPNOMC); @endphp
                                                        value="{{ $nombre ?? 'None' }}"
                                                        placeholder="{{ $carpeta->capbas->MPNOMC ?? 'None' }}" readonly>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Fecha Admision</label>
                                                    <input type="datetime" class="form-control" name="Fecha"
                                                        @php $fechaAdmision = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s.u', $carpeta->IngFecAdm); @endphp
                                                        value="{{ $fechaAdmision }}" readonly>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Numero Factura</label>
                                                    <input type="text" class="form-control" name="NumeroFactura"
                                                        @php $factura = trim($carpeta->IngFac); @endphp
                                                        value="{{ $factura ?? 'None' }}"
                                                        placeholder="{{ $factura ?? 'None' }}" readonly>
                                                </div>


                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-2">
                                                    <label>Codigo Contrato</label>
                                                    <input type="text" class="form-control" name="ContratoId"
                                                        @php $contratoID = trim($carpeta->IngNit); @endphp
                                                        value="{{ $contratoID ?? 'None' }}"
                                                        placeholder="{{ $contratoID ?? 'None' }}" readonly>
                                                </div>

                                                <div class="form-group col-md-9">
                                                    <label>Contrato</label>
                                                    <input type="text" class="form-control" name="Eps"
                                                        @php $eps = trim($carpeta->MENOMB); @endphp
                                                        value="{{ $eps ?? 'None' }}" placeholder="{{ $eps ?? 'None' }}"
                                                        readonly>
                                                </div>

                                                <div class="form-group col-md-1">
                                                    <label>Ingreso</label>
                                                    <input type="text" class="form-control" name="ConsecutivoId"
                                                        @php $CscId = trim($carpeta->IngCsc); @endphp
                                                        value="{{ $CscId ?? 'None' }}"
                                                        placeholder="{{ $CscId ?? 'None' }}" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                @csrf
                                                <input type="submit" value="Crear Buzon Soportes" class="btn btn-sm"
                                                    style="background:#0EA0A7; color:white;">
                                            </div>
                                        </form>
                                    @endforeach
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
