@extends('layouts.main', ['activePage' => 'compraCirugia', 'titlePage' => 'Editar Compras'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('compras.update', $compra->ProCirCod) }}" method="post" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Compra</h4>
                                <p class="card-category">Editar Compras</p>
                            </div>
                            <div class="card-body">

                                <div class="form-row">

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
                                                        placeholder="{{ $compra->ProRValAn == 'S' ? 'SI ES REQUERIDA' : 'NO ES REQUERIDA' }}"
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

                                    <div class="form-group col-md-4">
                                      <label for="MatQxAdq">ESTADO DE LA COMPRA</label>
                                        {{-- <strong>ESTADO DE LA COMPRA</strong> --}}
                                        <select class="form-control" name="MatQxAdq">
                                            @switch($compra->MatQxAdq)
                                                @case('PENDIENTE ')
                                                    <option value="PENDIENTE "
                                                        {{ $compra->MatQxAdq == 'PENDIENTE ' ? 'selected' : '' }}>PENDIENTE
                                                    </option>
                                                    <option value="EN TRAMITE"
                                                        {{ $compra->MatQxAdq == 'EN TRAMITE' ? 'selected' : '' }}>EN TRAMITE
                                                    </option>
                                                    <option value="COMPRADO"
                                                        {{ $compra->MatQxAdq == 'COMPRADO' ? 'selected' : '' }}>COMPRADO
                                                    </option>
                                                @break
                                                @case('EN TRAMITE')
                                                    <option value="PENDIENTE" disabled>PENDIENTE
                                                    </option>
                                                    <option value="EN TRAMITE"
                                                        {{ $compra->MatQxAdq == 'EN TRAMITE' ? 'selected' : '' }}>EN TRAMITE
                                                    </option>
                                                    <option value="COMPRADO"
                                                        {{ $compra->MatQxAdq == 'COMPRADO' ? 'selected' : '' }}>COMPRADO
                                                    </option>
                                                @break
                                                @case('COMPRADO')
                                                    <option value="PENDIENTE" disabled>PENDIENTE
                                                    </option>
                                                    <option value="EN TRAMITE" disabled>EN TRAMITE
                                                    </option>
                                                    <option value="COMPRADO"
                                                        {{ $compra->MatQxAdq == 'COMPRADO' ? 'selected' : '' }}>COMPRADO
                                                    </option>
                                                @break
                                                @default
                                                    <option value="ERROR" disabled>ESTADO NO CORRECTO
                                                    </option>
                                                    @break
                                                @endswitch

                                            </select>
                                      

                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="ObsMatQx">Observaciones De La Compra</label>
                                        <textarea class="form-control" id="ObsMatQx" name="ObsMatQx" rows="2"
                                            placeholder="{{ old('$compra->ObsMatQx', $compra->ObsMatQx) }}">{{  $compra->ObsMatQx }}</textarea>
                                    </div>


                                    <!--Footer-->
                                    <div class="card-footer ml-auto mr-auto">
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </div>
                                    <!--End footer-->
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
