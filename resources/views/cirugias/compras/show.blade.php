@extends('layouts.main', ['activePage' => 'compraCirugia', 'titlePage' => 'Detalles De La Compra'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="card-title">Usuarios</div>
                            <p class="card-category">Vista detallada del usuario: {{ $compra->MPNOMC }} -
                                Cirugia # {{ $compra->ProCirCod }}</p>
                        </div>
                        <!--body-->
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">

                                        <div class="card card-nav-tabs text-center">
                                            <div class="card-header card-header-primary">
                                                Informacion Del Paciente
                                            </div>
                                            <div class="card-body">

                                                <div class="form-row">
                                                    <div class="form-group col-md-2">
                                                        <label for="inputEmail4">Codigo De La Cirugia</label>
                                                        <input type="email" class="form-control" id="inputEmail4"
                                                            placeholder="{{ $compra->ProCirCod }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Tipo De Documento</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->MPTDoc }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword4">Numero De Documento</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->MPCedu }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="inputEmail4">Nombre Del Paciente</label>
                                                        <input type="email" class="form-control" id="inputEmail4"
                                                            placeholder="{{ $compra->MPNOMC }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                        <label for="inputPassword4">Edad</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->Edad }} Años" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Fecha De La Solicitud</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ \Carbon\Carbon::parse($compra->ProFSep)->format('d/m/Y') }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Sexo</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->MPSexo == 'F' ? 'FEMENINO' : 'MASCULINO' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label for="inputPassword4">EPS</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->EmpDsc }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="inputEmail4">Medico Que Solicita</label>
                                                        <input type="email" class="form-control" id="inputEmail4"
                                                            placeholder="{{ $compra->MMNomM }}" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-1">
                                                        <label for="inputEmail4">Cama</label>
                                                        <input type="email" class="form-control" id="inputEmail4"
                                                            placeholder="{{ $compra->TFCCODCAM }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword4">Pabellon</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->MPNomP }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Estado De La Cirugia</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProEsta }}" readonly>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword4">Valoracion Preanestesica</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProRValAn == 'S' ? 'REQUERIDO' : 'NO ES REQUERIDO' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword4">Estado De La Autorizacion</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->AutEstado }}" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-row">

                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">CUPS</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->crgcod }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-7">
                                                        <label for="inputPassword4">Procedimiento</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->prnomb }}" readonly>
                                                    </div>


                                                </div>

                                            </div>
                                        </div>

                                        <div class="card card-nav-tabs text-center">
                                            <div class="card-header card-header-primary">
                                                Datos De La Compra
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label for="inputEmail4">Materiales Especiales</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProReMaE == 'S' ? 'REQUERIDO' : 'NO ES REQUERIDO' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="inputPassword4">Descripcion De Materiales
                                                            Especiales</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProObMaE }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label for="inputEmail4">Dispositivos Especiales</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProDispEE == 'S' ? 'REQUERIDO' : 'NO ES REQUERIDO' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="inputPassword4">Descripcion De Dispositivos
                                                            Especiales</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProObsEE }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Estado De La Compra</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->MatQxAdq }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="inputEmail4">Fecha Del Tramite De La Compra</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ \Carbon\Carbon::parse($compra->FchCompTra)->format('d/m/Y') }}"
                                                            readonly>

                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Fecha De La Compra</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ \Carbon\Carbon::parse($compra->FchCompra)->format('d/m/Y') }}"
                                                            readonly>
                                                    </div>

                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <label for="ObsMatQx">Observaciones De La Compra</label>
                                                        <textarea class="form-control" id="ObsMatQx"
                                                            rows="2" placeholder="{{ $compra->ObsMatQx }}"
                                                            readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-nav-tabs text-center">
                                            <div class="card-header card-header-primary">
                                                Datos De La Reserva De La Sangre
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Estado De La Reserva</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ResHemDer }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Hemoderivados</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProReqHD }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="inputPassword4">Fecha De La Reserva DeL
                                                            Hemoderivado</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ \Carbon\Carbon::parse($compra->FchhemRes)->format('d/m/Y') }}"
                                                            readonly>
                                                    </div>

                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <label for="inputPassword4">Observaciones Hemoderivados</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProObsHD }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-nav-tabs text-center">
                                            <div class="card-header card-header-primary">
                                                Datos De La Reserva De La Cama
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="inputPassword4">Fecha De La Reserva De La Cama</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ \Carbon\Carbon::parse($compra->FchRexCam)->format('d/m/Y') }}"
                                                            readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword4">Requiere Reserva De Cama</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProResCam }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="inputPassword4">Estado De La Reserva</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ResCam }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="inputPassword4">Tipo De Cama</label>
                                                        <input type="password" class="form-control" id="inputPassword4"
                                                            placeholder="{{ $compra->ProTipCam }}" readonly>
                                                    </div>

                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <label for="inputEmail4">Observaciones Reserva De Cama</label>
                                                        <input type="email" class="form-control" id="inputEmail4"
                                                            placeholder="{{ $compra->ProObRCam }}" readonly>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="{{ route('compras.index') }}"
                                                    class="btn btn-sm btn-success mr-3"> Volver </a>
                                                @can('compra_edit')
                                                    <a href="{{ route('compras.edit', $compra->ProCirCod) }}"
                                                        class="btn btn-sm btn-twitter"> Editar </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end third-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
