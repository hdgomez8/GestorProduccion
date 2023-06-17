@extends('layouts.main', ['activePage' => 'facturacionVerPacientes', 'titlePage' => 'Adjuntar Documentos'])
@section('content')
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            Cargar Archivos
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('destroy'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('destroy') }}
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

                            <form action="{{ route('carpetas.guardar') }}" method="POST" enctype="multipart/form-data">
                                <div class="form-row">

                                    <input type="hidden" class="form-control" name="id" @php
                                        $id = trim($carpeta->id);
                                    @endphp
                                        value="{{ $id ?? 'None' }}" placeholder="{{ $id ?? 'None' }}" readonly>

                                    <div class="form-group col-md-2">
                                        <label>Tipo Identificacion</label>
                                        <input type="text" class="form-control" name="TipoIdentificacion"
                                            @php
                                                $tipoIdentificacion = trim($carpeta->MPTDoc);
                                            @endphp value="{{ $tipoIdentificacion ?? 'None' }}"
                                            placeholder="{{ $tipoIdentificacion ?? 'None' }}" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Numero Identificacion</label>
                                        <input type="text" class="form-control" name="NumeroIdentificacion"
                                            @php
                                                $numeroIdentificacion = trim($carpeta->MPCEDU);
                                            @endphp value="{{ $numeroIdentificacion ?? 'None' }}"
                                            placeholder="{{ $numeroIdentificacion ?? 'None' }}" readonly>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Nombre Paciente</label>
                                        <input type="text" class="form-control" name="Nombre" @php
                                            $nombre = trim($carpeta->MPNOMC);
                                        @endphp
                                            value="{{ $nombre ?? 'None' }}"
                                            placeholder="{{ $carpeta->MPNOMC ?? 'None' }}" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Fecha Admision</label>
                                        <input type="datetime" class="form-control" name="Fecha" @php
                                            $fechaAdmision = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $carpeta->IngFecAdm);
                                        @endphp
                                            value="{{ $fechaAdmision }}" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Numero Factura</label>
                                        <input type="text" class="form-control" name="NumeroFactura" @php
                                            $factura = trim($carpeta->IngFac);
                                        @endphp
                                            value="{{ $factura ?? 'None' }}"
                                            placeholder="{{ $carpeta->IngFac ?? 'None' }}" readonly>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label>Codigo Contrato</label>
                                        <input type="text" class="form-control" name="ContratoId" @php
                                            $contratoID = trim($carpeta->MENNIT);
                                        @endphp
                                            value="{{ $contratoID ?? 'None' }}" placeholder="{{ $carpeta->MENNIT }}"
                                            readonly>
                                    </div>

                                    <div class="form-group col-md-8">
                                        <label>Eps</label>
                                        <input type="text" class="form-control" name="Eps" @php
                                            $eps = trim($carpeta->MENOMB);
                                        @endphp
                                            value="{{ $eps ?? 'None' }}" placeholder="{{ $eps ?? 'None' }}" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Ingreso</label>
                                        <input type="text" class="form-control" name="ConsecutivoId" @php
                                            $CscId = trim($carpeta->IngCsc);
                                        @endphp
                                            value="{{ $CscId ?? 'None' }}" placeholder="{{ $CscId ?? 'None' }}"
                                            readonly>
                                    </div>
                                </div>
                                <br>
                                @can('facturacion_soportes_adjuntar_adjuntar')
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <label>Seleccione Nombre Documento:</label>

                                            <select class="form-control col-md-11" name="nombreDocumento1">
                                                <option value="NULLL" selected>Ingrese Nombre Documento</option>
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 2">ANEXO 2</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 3">ANEXO 3</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="AUTORIZACION AMBULATORIA">AUTORIZACION AMBULATORIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE URGENCIA">AUTORIZACION DE URGENCIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="CERTIFICADO DE ATENCION">CERTIFICADO DE ATENCION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="CERTIFICADO EPS">CERTIFICADO EPS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="COMPROBADOR DE DERECHO">COMPROBADOR DE DERECHO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="COTIZACION">COTIZACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="FACTURA">FACTURA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="GLUCOMETRIA">GLUCOMETRIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="HOJA DE GASTOS">HOJA DE GASTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE MEDICAMENTOS">HOJA DE MEDICAMENTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE LIQUIDOS">HOJA DE LIQUIDOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="INFORME QX">INFORME QX</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="INSUMOS">INSUMOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="NOTA DE ENFERMERIA">NOTA DE ENFERMERIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ORDEN MEDICA">ORDEN MEDICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="OXIGENO">OXIGENO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="RECORD DE ANESTESIA">RECORD DE ANESTESIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="RIESGO Y PREVENCION DE CAIDAS">RIESGO Y PREVENCION DE CAIDAS
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="SISBEN">SISBEN</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="SOLICITUD">SOLICITUD</option>
                                                @endcan
                                                <option value="SOPORTE">SOPORTE</option>
                                            </select>
                                            <input type="file" name="adjunto1">
                                        </div>

                                        <div>
                                            <label>Seleccione Nombre Documento:</label>

                                            <select class="form-control col-md-11" name="nombreDocumento2">
                                                <option value="NULLL" selected>Ingrese Nombre Documento</option>
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 2">ANEXO 2</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 3">ANEXO 3</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="AUTORIZACION AMBULATORIA">AUTORIZACION AMBULATORIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE URGENCIA">AUTORIZACION DE URGENCIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="CERTIFICADO DE ATENCION">CERTIFICADO DE ATENCION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="CERTIFICADO EPS">CERTIFICADO EPS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="COMPROBADOR DE DERECHO">COMPROBADOR DE DERECHO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="COTIZACION">COTIZACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="FACTURA">FACTURA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="GLUCOMETRIA">GLUCOMETRIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="HOJA DE GASTOS">HOJA DE GASTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE MEDICAMENTOS">HOJA DE MEDICAMENTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE LIQUIDOS">HOJA DE LIQUIDOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="INFORME QX">INFORME QX</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="INSUMOS">INSUMOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="NOTA DE ENFERMERIA">NOTA DE ENFERMERIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ORDEN MEDICA">ORDEN MEDICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="OXIGENO">OXIGENO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="RECORD DE ANESTESIA">RECORD DE ANESTESIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="RIESGO Y PREVENCION DE CAIDAS">RIESGO Y PREVENCION DE CAIDAS
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="SISBEN">SISBEN</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="SOLICITUD">SOLICITUD</option>
                                                @endcan
                                                <option value="SOPORTE">SOPORTE</option>
                                            </select>
                                            <input type="file" name="adjunto2">

                                        </div>
                                    </div>
                                    <br>


                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <label>Seleccione Nombre Documento:</label>

                                            <select class="form-control col-md-11" name="nombreDocumento3">
                                                <option value="NULLL" selected>Ingrese Nombre Documento</option>
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 2">ANEXO 2</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 3">ANEXO 3</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="AUTORIZACION AMBULATORIA">AUTORIZACION AMBULATORIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE URGENCIA">AUTORIZACION DE URGENCIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="CERTIFICADO DE ATENCION">CERTIFICADO DE ATENCION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="CERTIFICADO EPS">CERTIFICADO EPS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="COMPROBADOR DE DERECHO">COMPROBADOR DE DERECHO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="COTIZACION">COTIZACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="FACTURA">FACTURA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="GLUCOMETRIA">GLUCOMETRIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="HOJA DE GASTOS">HOJA DE GASTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE MEDICAMENTOS">HOJA DE MEDICAMENTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE LIQUIDOS">HOJA DE LIQUIDOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="INFORME QX">INFORME QX</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="INSUMOS">INSUMOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="NOTA DE ENFERMERIA">NOTA DE ENFERMERIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ORDEN MEDICA">ORDEN MEDICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="OXIGENO">OXIGENO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="RECORD DE ANESTESIA">RECORD DE ANESTESIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="RIESGO Y PREVENCION DE CAIDAS">RIESGO Y PREVENCION DE CAIDAS
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="SISBEN">SISBEN</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="SOLICITUD">SOLICITUD</option>
                                                @endcan
                                                <option value="SOPORTE">SOPORTE</option>
                                            </select>
                                            <input type="file" name="adjunto3">

                                        </div>

                                        <div>
                                            <label>Seleccione Nombre Documento:</label>

                                            <select class="form-control col-md-11" name="nombreDocumento4">
                                                <option value="NULLL" selected>Ingrese Nombre Documento</option>
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ADRES">ADRES</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 2">ANEXO 2</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="ANEXO 3">ANEXO 3</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE HOSPITALIZACION">AUTORIZACION DE HOSPITALIZACION
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="AUTORIZACION AMBULATORIA">AUTORIZACION AMBULATORIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="AUTORIZACION DE URGENCIA">AUTORIZACION DE URGENCIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="CERTIFICADO DE ATENCION">CERTIFICADO DE ATENCION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="CERTIFICADO EPS">CERTIFICADO EPS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="COMPROBADOR DE DERECHO">COMPROBADOR DE DERECHO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="COTIZACION">COTIZACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="FACTURA">FACTURA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="GLUCOMETRIA">GLUCOMETRIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="HISTORIA CLINICA">HISTORIA CLINICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="HOJA DE GASTOS">HOJA DE GASTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE MEDICAMENTOS">HOJA DE MEDICAMENTOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="HOJA DE LIQUIDOS">HOJA DE LIQUIDOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="IDENTIFICACION">IDENTIFICACION</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="INFORME QX">INFORME QX</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="INSUMOS">INSUMOS</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="NOTA DE ENFERMERIA">NOTA DE ENFERMERIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="ORDEN MEDICA">ORDEN MEDICA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="OXIGENO">OXIGENO</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_ambulatorio')
                                                    <option value="RECORD DE ANESTESIA">RECORD DE ANESTESIA</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_facturacion')
                                                    <option value="RIESGO Y PREVENCION DE CAIDAS">RIESGO Y PREVENCION DE CAIDAS
                                                    </option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_admisiones')
                                                    <option value="SISBEN">SISBEN</option>
                                                @endcan
                                                @can('facturacion_nombreDocumento_autorizacionesHos')
                                                    <option value="SOLICITUD">SOLICITUD</option>
                                                @endcan
                                                <option value="SOPORTE">SOPORTE</option>
                                            </select>
                                            <input type="file" name="adjunto4">

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        @csrf

                                        <input type="submit" value="Enviar" class="btn btn-lg btn-success">
                                    </div>
                                @endcan

                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header card-header-primary">
                            Soportes Adjuntos
                        </div>
                        <div class="card-body">

                            {{-- @if ($dir = opendir($ruta))
                                @while ($archivo = readdir($dir))
                                    @if ($archivo != '.' && $archivo != '..')
                                        <div class="col-sm-5 col-xs-12">
                                            <a href="../../Archivos/{{ $ruta2 }}/{{ $archivo }}"
                                                target="_blank"><strong>{{ $archivo }}</strong><br /></a>
                                        </div>
                                    @endif
                                @endwhile
                            @endif --}}

                            <div class="table-responsive">
                                <table class="table display compact" id="listadoArchivos" style="width:100%">
                                    <thead class="text-primary">
                                        <th>Nombre Archivo</th>
                                        <th>Guardado Por</th>
                                        <th>Fecha Guardado</th>
                                        <th class="text-right">Acciones</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($archivos as $archivo)
                                            <tr>
                                                <td><a href="../../Archivos/{{ $ruta2 }}/{{ $archivo->nombre_Archivo }}"
                                                        target="_blank">{{ $archivo->nombre_Archivo }}</a></td>
                                                <td>{{ $archivo->usuario }}</td>
                                                <td>{{ \Carbon\Carbon::parse($archivo->fecha_Guardado)->format('d/m/Y') }}
                                                </td>
                                                <td class="td-actions text-right">
                                                    @can('compra_edit')
                                                        {{-- <a href="{{ route('compras.edit', $archivo->nombre_Archivo) }}"
                                                            class="btn btn-warning"><i class="material-icons">edit</i></a> --}}
                                                        <input type="submit" value="Editar" class="btn btn-warning">
                                                    @endcan

                                                    <form action="{{ route('destroyArchivo.delete', $archivo->id) }}"
                                                        method="POST" style="display: inline-block;"
                                                        onsubmit="return confirm('¿Seguro Que Quieres Eliminar El Documento?')">

                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" class="form-control" name="idPaciente"
                                                            @php
                                                                $idPaciente = trim($carpeta->id);
                                                            @endphp value="{{ $idPaciente ?? 'None' }}"
                                                            placeholder="{{ $idPaciente ?? 'None' }}" readonly>
                                                        <input type="hidden" class="form-control" name="idArchivo"
                                                            @php
                                                                $idArchivo = trim($archivo->id);
                                                            @endphp value="{{ $idArchivo ?? 'None' }}"
                                                            placeholder="{{ $idArchivo ?? 'None' }}" readonly>
                                                        <input type="hidden" class="form-control" name="rutaArchivo"
                                                            @php
                                                                $rutaArchivo = trim($archivo->ruta);
                                                                $rutaArchivoSin = substr($rutaArchivo, 2, -1);
                                                                $rutaArchivoSin = str_replace('\\', '\\\\', $rutaArchivoSin);
                                                            @endphp
                                                            value="{{ $archivo->ruta }}{{ $archivo->nombre_Archivo }}"
                                                            placeholder="{{ $rutaArchivo ?? 'None' }}" readonly>

                                                        @if (trim(auth()->user()->username) == trim($archivo->usuario))
                                                            <input type="submit" value="Eliminar" class="btn btn-danger">
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @can('facturacion_alistamiento')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                Alistar Soportes Adjuntos
                            </div>
                            <div class="container">
                                <div class="row mt-3">
                                    <div class="col-5 my-4">
                                        <h2>Alistar</h2>
                                        <div class="list-group" id="listado_Archivos">
                                            @foreach ($archivos as $archivo)
                                                <div class="list-group-item mb-0" data-id="{{ $archivo->id }}">
                                                    <li style="list-style-type: none;">
                                                        <i class="fass material-icons mr-2">swap_vert</i>
                                                        <a href="{{ $archivo->ruta }}{{ $archivo->nombre_Archivo }}"
                                                            target="_blank">{{ $archivo->nombre_Archivo }}</a>
                                                    </li>
                                                </div>
                                            @endforeach
                                        </div>

                                        <form id="formularioUnirPdf">
                                            @csrf
                                            <div><label for="numeroFactura">Numero De Factura</label>

                                                <input type="text" id="numeroFactura" name="numeroFactura" required
                                                    minlength="4" maxlength="15" size="15">
                                            </div>
                                            <button class="btn btn-sm btn-primary" type="submit">Unir PDF</button>
                                        </form>
                                    </div>
                                    <div class="col-5 my-4">
                                        <h2>No Alistar</h2>
                                        <div class="list-group" id="listado_Archivos_SinConcatenar">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('adjuntar') == 'Debes adjuntar Algo')
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'No has adjuntado ningun documento',
                text: 'Debes Adjuntar Al Menos Un Documento...!'
            })
        </script>
    @endif

    <script>
        var id = {!! $id !!};
        var ruta = "{!! $rutaArchivoSin !!}";
        let ordenArchivos;
        //const ordenArchivos = [];
        var listado_Archivos = document.getElementById('listado_Archivos');
        var listado_Archivos_SinConcatenar = document.getElementById('listado_Archivos_SinConcatenar');

        var listado_Archivos_Informacion = Sortable.create(listado_Archivos, {
            group: {
                name: "Listado_Archivos",
            },
            animation: 300,
            easing: "cubic-bezier(0.7, 0, 0.84, 0)",
            handle: ".fass",
            ghostClass: "active",
            store: {
                set: function(sortable) {
                    ordenArchivos = [];
                    //console.log(sortable);
                    var orden = sortable.toArray();
                    //document.getElementById("ulListado").innerHTML = JSON.stringify(orden);
                    //console.log(orden);
                    localStorage.setItem("lista-archivos", orden.join("|"));
                    orden.forEach(element => {
                        //mostrar ruta
                        //console.log(element);
                        var elemento = document.querySelector('[data-id="' + element + '"]');
                        //console.log(elemento);
                        if (elemento) {
                            const li = elemento.children[0];
                            const a = li.children[1];
                            const path = a.getAttribute("href");
                            ordenArchivos.push(path);
                            //console.log(path);
                        }
                    });
                    localStorage.setItem("archivosOrdenados", JSON.stringify(ordenArchivos));
                },
                get: function(sortable) {
                    var orden = localStorage.getItem("lista-archivos");
                    return orden ? orden.split("|") : [];
                }
            }
        });

        Sortable.create(listado_Archivos_SinConcatenar, {
            group: {
                name: "Listado_Archivos",

            },
            animation: 300,
            easing: "cubic-bezier(0.7, 0, 0.84, 0)",
            handle: ".fass",
            ghostClass: "active",
        });

        $('#formularioUnirPdf').on('submit', function(e) {
            e.preventDefault();
            const ordenNuevo = localStorage.getItem('archivosOrdenados');
            var numeroFactura = document.getElementById("numeroFactura").value;
            //console.log('evento submit formulario',JSON.parse(ordenNuevo));
            $.ajax({
                url: "/clinicamc/public/unirPdf",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    orden: JSON.parse(ordenNuevo),
                    id: id,
                    ruta: ruta,
                    factura: numeroFactura,
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(response) {
                    console.log(response);
                },
            });
        });
    </script>
@endsection
