@extends('layouts.main', ['activePage' => 'facturacionSalidas', 'titlePage' => 'Editar Salidas'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="#" method="post" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Salidas</h4>
                                <p class="card-category">Editar Salidas</p>
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
                                                    <label for="inputPassword4">Fecha De La Solicitud</label>
                                                    <input type="password" class="form-control" id="inputPassword4"
                                                        placeholder="{{$salida->HISCKEY}}" readonly>
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <label for="inputPassword4">EPS</label>
                                                    <input type="password" class="form-control" id="inputPassword4"
                                                        placeholder="{{ $salida->HISCKEY }}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="inputPassword4">Sexo</label>
                                                    <input type="password" class="form-control" id="inputPassword4"
                                                        placeholder="Datos" readonly>
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <label for="inputPassword4">EPS</label>
                                                    <input type="password" class="form-control" id="inputPassword4"
                                                        placeholder="Datos" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="inputEmail4">Medico Que Solicita</label>
                                                    <input type="email" class="form-control" id="inputEmail4"
                                                        placeholder="#" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Footer-->
                                    <div class="card-footer ml-auto mr-auto">
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </div>
                                    <!--End footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
