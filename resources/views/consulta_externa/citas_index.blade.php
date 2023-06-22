@extends('layouts.main', ['activePage' => 'CitasColsalud', 'titlePage' => 'Citas'])
@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">CITAS</h4>
                        <p class="card-category">CAMBIAR ESTADOS DE CITA</p>
                    </div>
                    <form action="{{ route('cambiar_estado_cita') }}" method="post">
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
                            <input type="hidden" name="nombre_formulario" value="CAMBIAR_ESTADO_CITA">
                            <input type="hidden" name="accion" value="EDITAR">
                            <input type="hidden" id="valor_cita_antes" name="valor_cita_antes" value="{{ old('valor_cita_antes') }}">
                            <div class="row justify-content-center">
                                <label for="numero_cita" class="col-form-label" style="color: #333333; font-weight: bold;">NUMERO DE LA CITA</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="numero_cita" name="numero_cita" value="{{ old('numero_cita') }}" placeholder="Ingrese Numero Cita" data-toggle="modal" data-target="#popup_numero_cita" onclick="this.blur();" onkeydown="return false;" required>
                                </div>
                                <label for="estado_cita" class="col-form-label" style="color: #333333; font-weight: bold;">ESTADO DE LA CITA</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="estado_cita" name="estado_cita" value="{{ old('valor_cita_antes') }}" disabled>
                                </div>
                                <label for="actualizar_estado_cita" class="col-form-label" style="color: #333333; font-weight: bold;">SELECCIONE ESTADO</label>
                                <div class="col-sm-2">
                                    <select class="form-control" name="actualizar_estado_cita" id="actualizar_estado_cita" required>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center my-2">
                                <label for="observacion" class="col-form-label text-left" style="color: #333333; font-weight: bold;">OBSERVACIÓN</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="observacion" name="observacion" maxlength="100" value="{{ old('observacion') }}" required>
                                    <p id="mensaje-caracteres" style="color: red; display: none;">Se ha excedido el límite de caracteres.</p>
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

<div id="popup_numero_cita" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Numero Cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="filtroNumeroCita" placeholder="Escribe para filtrar">

                <table id="tablaNumeroCita" class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>NUMERO CITA</th>
                            <th>ESTADO CITA</th>
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


<script>
    $(document).ready(function() {
        $('#filtroNumeroCita').on('input', function() {
            var filtro = $(this).val();

            $.ajax({
                url: "{{ route('filtroNumeroCita') }}",
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
                            filas += '<td>' + response[i].CitNum + '</td>';
                            if (response[i].CitEstP === 'A') {
                                filas += '<td style="background-color: #0000c0; color: white; font-weight: bold;">Atendida</td>';
                            }
                            if (response[i].CitEstP === 'C') {
                                filas += '<td style="background-color: #00ff00; color: black; font-weight: bold;">Confirmada</td>';
                            }
                            if (response[i].CitEstP === 'R') {
                                filas += '<td style="background-color: #ffff00; color: black; font-weight: bold;">Reservada</td>';
                            }
                            if (response[i].CitEstP === 'I') {
                                filas += '<td style="background-color: #c00000; color: black; font-weight: bold;">Incumplida</td>';
                            }
                            if (response[i].CitEstP === 'F') {
                                filas += '<td style="background-color: #008080; color: black; font-weight: bold;">Facturada</td>';
                            }
                            filas += '</tr>';
                        }
                    } else {
                        filas = '<tr><td colspan="3">No se encontraron resultados</td></tr>';
                    }

                    $('#tablaNumeroCita tbody').html(filas);
                },
                error: function() {
                    console.log('Error en la solicitud AJAX');
                }
            });
        });

        // Agrega evento de clic a las filas de la tabla
        $('#tablaNumeroCita tbody').on('click', 'tr', function() {
            var numeroCita = $(this).find('td:first').text();
            var estadoCita = $(this).find('td:nth-child(2)').text();

            // Asigna los valores seleccionados al campo de entrada de texto
            $('#numero_cita').val(numeroCita);
            $('#estado_cita').val(estadoCita);

            if (estadoCita === "Atendida") {
                $('#valor_cita_antes').val("A");
            }
            if (estadoCita === "Confirmada") {
                $('#valor_cita_antes').val("C");
            }
            if (estadoCita === "Reservada") {
                $('#valor_cita_antes').val("R");
            }


            var backgroundColor;
            if (estadoCita === "Atendida") {
                backgroundColor = "#0000c0"; // Blue background color for value "A"
            } else if (estadoCita === "Confirmada") {
                backgroundColor = "#00ff00"; // Green background color for value "B"
            } else if (estadoCita === "Reservada") {
                backgroundColor = "#ffff00"; // Green background color for value "B"
            } else if (estadoCita === "Incumplida") {
                backgroundColor = "#c00000"; // Green background color for value "B"
            } else if (estadoCita === "Facturada") {
                backgroundColor = "#008080"; // Green background color for value "B"
            }

            $('#estado_cita').css('background-color', backgroundColor);
            $('#estado_cita').css('font-weight', 'bold');

            var color;
            if (backgroundColor === "#ffff00") {
                color = "black";
            } else {
                color = "white";
            }
            $('#estado_cita').css('color', color);

            // Cierra el popup
            $('#popup_numero_cita').hide();
            $('.modal-backdrop').hide();

            // Get references to the elements
            const estadoCitaInput = document.getElementById('valor_cita_antes');
            const actualizarEstadoCitaSelect = document.getElementById('actualizar_estado_cita');

            // Add options to the select menu based on the initial value of estado_cita
            if (estadoCitaInput.value === 'A') {
                // Only add "INCUMPLIDA" option when the initial state is "ATENDIDA"
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Seleccione Estado';
                actualizarEstadoCitaSelect.add(option);
                const option1 = document.createElement('option');
                option1.value = 'I';
                option1.text = 'Incumplida';
                actualizarEstadoCitaSelect.add(option1);
            } else if (estadoCitaInput.value === 'C') {
                // Add both options "ATENDIDA" and "INCUMPLIDA"
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Seleccione Estado';
                actualizarEstadoCitaSelect.add(option); 

                const option1 = document.createElement('option');
                option1.value = 'A';
                option1.text = 'Atendida';
                actualizarEstadoCitaSelect.add(option1);

                const option2 = document.createElement('option');
                option2.value = 'I';
                option2.text = 'Incumplida';
                actualizarEstadoCitaSelect.add(option2);
            } else if (estadoCitaInput.value === 'R') {
                // Add both options "ATENDIDA" and "INCUMPLIDA"
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Seleccione Estado';
                actualizarEstadoCitaSelect.add(option); 

                const option1 = document.createElement('option');
                option1.value = 'A';
                option1.text = 'Atendida';
                actualizarEstadoCitaSelect.add(option1);

                const option2 = document.createElement('option');
                option2.value = 'I';
                option2.text = 'Incumplida';
                actualizarEstadoCitaSelect.add(option2);
            }

            // Update the input field and select menu when the selected option changes
            actualizarEstadoCitaSelect.addEventListener('change', function() {
                estadoCitaInput.value = this.value;
                console.log(estadoCitaInput.value);
            });
        });

        // Logica para mostrar los datos segun dato anterior del valor de la cita
        // Obtén el valor antiguo del campo estado_cita
        var estadoCitaAntiguo = "{{ old('valor_cita_antes') }}";

        // Asigna el valor antiguo al campo estado_cita
        $('#estado_cita').val(estadoCitaAntiguo);

        // Aplica la lógica de asignación de valor y color de fondo
        if (estadoCitaAntiguo === "A") {
            $('#estado_cita').val("Atendida");
            $('#estado_cita').css('color', 'white');
            $('#estado_cita').css('font-weight', 'bold');
            $('#estado_cita').css('background-color', '#0000c0');
        } else if (estadoCitaAntiguo === "C") {
            $('#estado_cita').val("Confirmada");
            $('#estado_cita').css('color', 'black');
            $('#estado_cita').css('font-weight', 'bold');
            $('#estado_cita').css('background-color', '#00ff00');
        } else if (estadoCitaAntiguo === "R") {
            $('#estado_cita').val("Reservada");
            $('#estado_cita').css('color', 'black');
            $('#estado_cita').css('font-weight', 'bold');
            $('#estado_cita').css('background-color', '#ffff00');
        }
    });
</script>


@endsection