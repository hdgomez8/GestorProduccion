@extends('layouts.main', ['activePage' => 'ParametrizacionesIndex', 'titlePage' => 'ParametrizacionesIndex'])
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
                        <h4 class="card-title">PARAMETRIZACIONES</h4>
                        <p class="card-category">VALOR VARIABLE </p>
                    </div>
                    <form action="#" method="post">
                        @csrf
                        <div class="card-body">

                            <div class="row justify-content-center">
                                <div class="col-sm-3">
                                    <label for="nombreCapita">SELECCIONA</label>
                                    <select id="opciones" class="form-control">
                                        <option value="1">PROCEDIMIENTO</option>
                                        <!-- <option value="2">SUMINISTRO</option> -->
                                    </select>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card" id="div1" style="display:none">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">PROCEDIMIENTO</h4>
                        <p class="card-category">VALOR VARIABLE PROCEDIMIENTO</p>
                    </div>
                    <form action="{{ route('actualizarValorVariable') }}" method="post">
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
                            <input type="hidden" name="nombre_formulario" value="VALOR_VARIABLE">
                            <input type="hidden" name="accion" value="EDITAR">
                            <div class="row justify-content-start">
                                <div class="col-sm-2">
                                    <label for="codigo_portafolio">Codigo Portafolio</label>
                                    <input type="text" class="form-control" id="codigo_portafolio" name="codigo_portafolio" placeholder="Ingrese Codigo Portafolio" autofocus data-toggle="modal" data-target="#popup_portafolio" required>
                                </div>
                                <div class="col-sm-10">
                                    <label for="nombre_portafolio">Nombre Portafolio</label>
                                    <input type="text" class="form-control" id="nombre_portafolio" name="nombre_portafolio" disabled>
                                </div>
                                <div class="col-sm-2">
                                    <label for="codigo_procedimiento">Codigo Procedimiento</label>
                                    <input type="text" class="form-control" id="codigo_procedimiento" name="codigo_procedimiento" placeholder="Ingrese Codigo Procedimiento" data-toggle="modal" data-target="#popup_procedimiento" required disabled>
                                </div>
                                <div class="col-sm-10">
                                    <label for="nombre_procedimiento">Nombre Procedimiento</label>
                                    <input type="text" class="form-control" id="nombre_procedimiento" name="nombre_procedimiento" disabled>
                                </div>
                                <input type="hidden" id="valor_variable_antes" name="valor_variable_antes">
                                <div class="col-sm-2">
                                    <label for="valor_variable_anterior">Valor Variable</label>
                                    <input type="text" class="form-control" id="valor_variable_anterior" name="valor_variable_anterior" disabled>
                                </div>
                                <div class="col-sm-2">
                                    <label>Actualizar Valor Variable</label><br>
                                    <div class="text-center">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="opciones_valorVariable" id="inlineRadio1" value="S" required>
                                            <label for="inlineRadio1">SI</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="opciones_valorVariable" id="inlineRadio2" value="N">
                                            <label for="inlineRadio2">NO</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-8">
                                    <label for="observacion">Observación</label>
                                    <input type="text" class="form-control" id="observacion" name="observacion" maxlength="100" required>
                                    <p id="mensaje-caracteres" style="color: red; display: none;">Se ha excedido el límite de caracteres.</p>
                                </div>
                            </div>

                            <div class="row justify-content-center my-1">
                                <div class="col-2 text-right">
                                    <input type="submit" id="btn_actualizar_valorVariable" value="GUARDAR" class="btn btn-sm" style="background:#0EA0A7; color:white;">
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

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="div2" style="display:none">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">SUMINISTRO</h4>
                        <p class="card-category">VALOR VARIABLE SUMINISTRO</p>
                    </div>
                    <form action="{{ route('reportes.subsidiadoAC') }}" method="post">
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
                            <!-- <input type="hidden" name="nombre_formulario" value="VALOR_VARIABLE">
                            <input type="hidden" name="accion" value="EDITAR">
                            <div class="row justify-content-start">
                                <div class="col-sm-2">
                                    <label for="codigo_portafolio">Codigo Portafolio</label>
                                    <input type="text" class="form-control" id="codigo_portafolio" name="codigo_portafolio" placeholder="Ingrese Codigo Portafolio" autofocus data-toggle="modal" data-target="#popup_portafolio" required>
                                </div>
                                <div class="col-sm-10">
                                    <label for="nombre_portafolio">Nombre Portafolio</label>
                                    <input type="text" class="form-control" id="nombre_portafolio" name="nombre_portafolio" disabled>
                                </div>
                                <div class="col-sm-2">
                                    <label for="codigo_procedimiento">Codigo Procedimiento</label>
                                    <input type="text" class="form-control" id="codigo_procedimiento" name="codigo_procedimiento" placeholder="Ingrese Codigo Procedimiento" data-toggle="modal" data-target="#popup_procedimiento" required disabled>
                                </div>
                                <div class="col-sm-10">
                                    <label for="nombre_procedimiento">Nombre Procedimiento</label>
                                    <input type="text" class="form-control" id="nombre_procedimiento" name="nombre_procedimiento" disabled>
                                </div>
                                <input type="hidden" id="valor_variable_antes" name="valor_variable_antes">
                                <div class="col-sm-2">
                                    <label for="valor_variable_anterior">Valor Variable</label>
                                    <input type="text" class="form-control" id="valor_variable_anterior" name="valor_variable_anterior" disabled>
                                </div>
                                <div class="col-sm-2">
                                    <label for="opciones_valorVariable">Actualizar Valor Variable</label>
                                    <select id="opciones_valorVariable" name="opciones_valorVariable" class="form-control">
                                        <option value="S">SI</option>
                                        <option value="N">NO</option>
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <label for="observacion">Observación</label>
                                    <input type="text" class="form-control" id="observacion" name="observacion" maxlength="100" required>
                                    <p id="mensaje-caracteres" style="color: red; display: none;">Se ha excedido el límite de caracteres.</p>
                                </div>
                            </div>

                            <div class="row justify-content-center my-1">
                                <div class="col-2 text-right">
                                    <input type="submit" id="btn_actualizar_valorVariable" value="GUARDAR" class="btn btn-sm" style="background:#0EA0A7; color:white;">
                                </div>
                                <div class="col-2 text-left">
                                    <a href="/clinicamc/public/" class="btn btn-sm btn-secondary" style="background:red; color:white;">CANCELAR</a>
                                </div>
                            </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="popup_portafolio" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Portafolios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="filtroPP" placeholder="Escribe para filtrar">

                <table id="tablaPP" class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>CODIGO PORTAFOLIO</th>
                            <th>NOMBRE PORTAFOLIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se generarán las filas de la tabla -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="popup_procedimiento" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Procedimientos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="filtroP" placeholder="Escribe para filtrar">

                <table id="tablaP" class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>CODIGO PROCEDIMIENTO</th>
                            <th>NOMBRE PROCEDIMIENTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se generarán las filas de la tabla -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')

<script>
    // Función para mostrar u ocultar los div según el valor seleccionado en el select
    function mostrarOcultarDivs() {
        const opcionesSelect = document.getElementById('opciones');
        const div1 = document.getElementById('div1');
        const div2 = document.getElementById('div2');

        if (opcionesSelect.value === '1') {
            div1.style.display = 'block';
            div2.style.display = 'none';
        } else if (opcionesSelect.value === '2') {
            div1.style.display = 'none';
            div2.style.display = 'block';
        }
    }

    // Agregar el evento onchange al select para que se ejecute la función cada vez que cambie el valor
    document.getElementById('opciones').addEventListener('change', mostrarOcultarDivs);

    // Mostrar u ocultar los div al cargar la página según el valor inicial del select
    mostrarOcultarDivs();
</script>

<script>
    $(document).ready(function() {
        document.getElementById('filtroPP').focus();
        $('#filtroPP').on('input', function() {
            var filtro = $(this).val();

            $.ajax({
                url: "{{ route('filtro') }}",
                type: 'GET',
                data: {
                    filtro: filtro
                },
                success: function(response) {
                    // Genera las filas de la tabla con los resultados filtrados
                    var filas = '';

                    if (response.length > 0) {
                        for (var i = 0; i < response.length; i++) {
                            filas += '<tr>';
                            filas += '<td>' + response[i].PTCodi + '</td>';
                            filas += '<td>' + response[i].PTDesc + '</td>';
                            filas += '</tr>';
                        }
                    } else {
                        filas = '<tr><td colspan="3">No se encontraron resultados</td></tr>';
                    }

                    $('#tablaPP tbody').html(filas);
                },
                error: function() {
                    console.log('Error en la solicitud AJAX');
                }
            });
        });



        // Agrega evento de clic a las filas de la tabla
        $('#tablaPP tbody').on('click', 'tr', function() {
            var codigo = $(this).find('td:first').text();
            var descripcion = $(this).find('td:nth-child(2)').text();

            // Asigna los valores seleccionados al campo de entrada de texto
            $('#codigo_portafolio').val(codigo);
            $('#nombre_portafolio').val(descripcion);

            var codigoPortafolioInput = document.getElementById("codigo_portafolio");
            var codigoProcedimientoInput = document.getElementById("codigo_procedimiento");

            if (codigoPortafolioInput.value) {
                codigoProcedimientoInput.disabled = false;
            } else {
                codigoProcedimientoInput.disabled = true;
            }


            // Cierra el popup
            $('#popup_portafolio').hide();
            $('.modal-backdrop').hide();

        });
    });
</script>

<script>
    $(document).ready(function() {
        var codigo_portafolio;

        $('#filtroP').on('input', function() {
            var filtro = $(this).val();
            var codigo_portafolio = $('#codigo_portafolio').val();

            $.ajax({
                url: "{{ route('filtroP') }}",
                type: 'GET',
                data: {
                    filtro: filtro,
                    codigo_portafolio: codigo_portafolio
                },
                success: function(response) {
                    // Genera las filas de la tabla con los resultados filtrados
                    var filas = '';

                    if (response.length > 0) {

                        for (var i = 0; i < response.length; i++) {
                            filas += '<tr>';
                            filas += '<td>' + response[i].PRCODI + '</td>';

                            // Realizar consulta para obtener el valor de PrNomb
                            $.ajax({
                                url: "{{ route('obtenerNombreProcedimiento') }}",
                                type: 'GET',
                                data: {
                                    prcodi: response[i].PRCODI
                                },
                                async: false, // Esperar a que la consulta se complete antes de continuar el bucle
                                success: function(nombre) {
                                    filas += '<td>' + nombre[0].PrNomb + '</td>';
                                },
                                error: function() {
                                    console.log('Error en la solicitud AJAX');
                                }
                            });

                            filas += '</tr>';
                        }
                    } else {
                        filas = '<tr><td colspan="3">No se encontraron resultados</td></tr>';
                    }

                    $('#tablaP tbody').html(filas);
                },
                error: function() {
                    console.log('Error en la solicitud AJAX');
                }
            });
        });

        // Agrega evento de clic a las filas de la tabla
        $('#tablaP tbody').on('click', 'tr', function() {
            var codigo = $(this).find('td:first').text();
            var descripcion = $(this).find('td:nth-child(2)').text();
            var codigo_portafolio = $('#codigo_portafolio').val();

            // Asigna los valores seleccionados al campo de entrada de texto
            $('#codigo_procedimiento').val(codigo);
            var codigo_procedimiento = codigo;

            $('#nombre_procedimiento').val(descripcion);

            // Cierra el popup
            $('#popup_procedimiento').hide();
            $('.modal-backdrop').hide();


            // Realizar la consulta AJAX utilizando los valores ingresados
            $.ajax({
                url: "{{ route('obtenerValorVariable') }}",
                type: 'GET',
                data: {
                    codigo_portafolio: codigo_portafolio,
                    codigo_procedimiento: codigo_procedimiento,
                    // Agrega otros valores necesarios para la consulta
                },
                success: function(response) {
                    // Manejar los resultados de la consulta
                    // Puedes utilizar los resultados para determinar el nuevo valor del otro campo

                    var solucion = response[0].PTValLib;

                    // Actualizar el valor del campo de opciones
                    if (solucion === 'S') {
                        $('#valor_variable_anterior').val('SI');
                        $('#valor_variable_antes').val('S');
                    } else {
                        // Si la solución no coincide con ninguno de los casos, se puede asignar un valor predeterminado
                        $('#valor_variable_anterior').val('NO');
                        $('#valor_variable_antes').val('N');
                    }
                },
                error: function() {
                    console.log('Error en la solicitud AJAX');
                }
            });
        });
    });
</script>

<script>
    const observacionInput = document.getElementById('observacion');
    const mensajeCaracteres = document.getElementById('mensaje-caracteres');

    observacionInput.addEventListener('input', function() {
        if (observacionInput.value.length > 100) {
            observacionInput.value = observacionInput.value.slice(0, 100);
            mensajeCaracteres.style.display = 'block';
        } else {
            mensajeCaracteres.style.display = 'none';
        }
    });
</script>

@endsection