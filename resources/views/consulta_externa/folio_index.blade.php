@extends('layouts.main', ['activePage' => 'FoliosColsalud', 'titlePage' => 'Folios'])
@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">FOLIOS</h4>
                        <p class="card-category">CERRAR FOLIOS DE HISTORIA CLINICA</p>
                    </div>
                    <form action="{{ route('cerrar_folio') }}" method="post">
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
                            <input type="hidden" name="nombre_formulario" value="CERRAR_FOLIO">
                            <input type="hidden" name="accion" value="EDITAR">
                            <div class="row justify-content-start text-center">
                                <label for="tipoDocumento" class="col-sm-2 col-form-label" style="color: #333333; font-weight: bold;">Tipo Documento</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="tipoDocumento" id="tipoDocumento" title="Completa este campo">
                                        <option value="X" {{ old('tipoDocumento') == 'X' ? 'selected' : '' }}>Seleccione Tipo Documento</option>
                                        <option value="AS" {{ old('tipoDocumento') == 'AS' ? 'selected' : '' }}>ADULTO SIN IDENTIFICAR</option>
                                        <option value="CD" {{ old('tipoDocumento') == 'CD' ? 'selected' : '' }}>CARNET DIPLOMATICO</option>
                                        <option value="CC" {{ old('tipoDocumento') == 'CC' ? 'selected' : '' }}>CEDULA DE CIUDADANIA</option>
                                        <option value="CE" {{ old('tipoDocumento') == 'CE' ? 'selected' : '' }}>CEDULA DE EXTRANJERIA</option>
                                        <option value="CN" {{ old('tipoDocumento') == 'CN' ? 'selected' : '' }}>CERTIFICADO DE NACIDO VIVO</option>
                                        <option value="MS" {{ old('tipoDocumento') == 'MS' ? 'selected' : '' }}>MENOR SIN IDENTIFICAR</option>
                                        <option value="NI" {{ old('tipoDocumento') == 'NI' ? 'selected' : '' }}>NIT</option>
                                        <option value="NUI" {{ old('tipoDocumento') == 'NUI' ? 'selected' : '' }}>NUMERO UNICO DE IDENTIFICACION</option>
                                        <option value="PA" {{ old('tipoDocumento') == 'PA' ? 'selected' : '' }}>PASAPORTE</option>
                                        <option value="PE" {{ old('tipoDocumento') == 'PE' ? 'selected' : '' }}>PERMISO ESPECIAL DE PERMANENCIA</option>
                                        <option value="PT" {{ old('tipoDocumento') == 'PT' ? 'selected' : '' }}>PERMISO POR PROTECCION TEMPORAL</option>
                                        <option value="RC" {{ old('tipoDocumento') == 'RC' ? 'selected' : '' }}>REGISTRO CIVIL</option>
                                        <option value="RE" {{ old('tipoDocumento') == 'RE' ? 'selected' : '' }}>RESIDENTE ESPECIAL PARA LA PAZ</option>
                                        <option value="SC" {{ old('tipoDocumento') == 'SC' ? 'selected' : '' }}>SALVO CONDUCTO</option>
                                        <option value="TI" {{ old('tipoDocumento') == 'TI' ? 'selected' : '' }}>TARJETA DE IDENTIDAD</option>
                                    </select>
                                    <small id="tipoDocumentoError" class="text-danger d-none">Seleccione un tipo de documento.</small>
                                </div>

                                <label for="name" class="col-sm-2 col-form-label" style="color: #333333; font-weight: bold;">Número Documento</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="numeroDocumento" id="numeroDocumento" placeholder="Ingrese Numero Documento" value="{{ old('numeroDocumento') }}" disabled required>
                                </div>
                            </div>

                            <div class="row justify-content-center my-1">
                                <div class="col-2 text-right">
                                    <input type="submit" value="GUARDAR" class="btn btn-sm" style="background:#0EA0A7; color:white;">
                                </div>
                                <div class="col-2 text-left">
                                    <a href="/clinicamc/public/" class="btn btn-sm btn-secondary" style="background:gray; color:white;">CANCELAR</a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Validate form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        var tipoDocumento = document.getElementById('tipoDocumento');
        var tipoDocumentoError = document.getElementById('tipoDocumentoError');

        if (tipoDocumento.value === '') {
            tipoDocumentoError.classList.remove('d-none');
            event.preventDefault(); // Prevent form submission
        } else {
            tipoDocumentoError.classList.add('d-none');
        }
    });

    var tipoDocumentoSelect = document.getElementById("tipoDocumento");
    var numeroDocumentoInput = document.getElementById("numeroDocumento");

    tipoDocumentoSelect.addEventListener("change", function() {
        if (tipoDocumentoSelect.value !== "X") {
            numeroDocumentoInput.disabled = false;
        } else {
            numeroDocumentoInput.disabled = true;
            numeroDocumentoInput.value = ""; // También puedes restablecer el valor del campo
        }
    });
</script>
@endsection