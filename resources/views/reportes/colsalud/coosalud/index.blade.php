@extends('layouts.main', ['activePage' => 'ReportesIndexColsaludCoosalud', 'titlePage' => 'ReportesIndexColsalud'])
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
<link href="{{ asset('css/estilos.css') }}" rel="stylesheet" />

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">CAPITA COOSALUD</h4>
                        <p class="card-category">CAPITA COOSALUD - CONTRIBUTIVO - SUBSIDIADO </p>
                    </div>
                    <form action="#" method="post">
                        @csrf
                        <div class="card-body">

                            <div class="row justify-content-center">
                                <div class="col-sm-3">
                                    <label for="nombreCapita">SELECCIONA REGIMEN</label>
                                    <select id="opciones" class="form-control">
                                        <option value="1">CONTRIBUTIVO</option>
                                        <option value="2">SUBSIDIADO</option>
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
                        <h4 class="card-title">CONTRIBUTIVO</h4>
                        <p class="card-category">CAPITA CONTRIBUTIVA</p>
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
                            <div class="row justify-content-start">
                                <div class="col-sm-3">
                                    <label for="nombreCapita">Capita</label>
                                    <select class="form-control" name="nombreCapita">
                                        <option value=" ">Ingrese Nombre Capita</option>
                                        <option value="COL0419">CARDIOVASCULAR</option>
                                        <option value="COL0449','COL0448">GASTROENTEROLOGIA</option>
                                        <option value="COL0429">NEUMOLOGIA</option>
                                        <option value="COL0434','COL0432">NEUROLOGIA</option>
                                        <option value="COL0426','COL0427">UROLOGIA</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="numeroFactura">Numero Factura</label>
                                    <input type="text" class="form-control" id="numeroFactura" name="numeroFactura" placeholder="Numero Factura" autofocus>
                                </div>
                                <div class="col-sm-2">
                                    <label for="valorFactura">Valor Factura</label>
                                    <input type="text" class="form-control" id="valorFactura" name="valorFactura" placeholder="Valor Factura" autofocus>
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_field">Fecha Inicio:</label>
                                    <input type="date" name="inicio" id="date_field" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_field">Fecha Final:</label>
                                    <input type="date" name="fin" id="date_field" class="form-control">
                                </div>

                            </div>

                            <div class="row justify-content-center my-1">
                                <div class="col-2 text-right">
                                    <input type="submit" value="DESCARGAR" class="btn btn-sm" style="background:#0EA0A7; color:white;">
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
                        <h4 class="card-title">SUBSIDIADO</h4>
                        <p class="card-category">CAPITA SUBSIDIADA</p>
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
                            <div class="row justify-content-start">
                                <div class="col-sm-3">
                                    <label for="nombreCapita">Capita</label>
                                    <select class="form-control" name="nombreCapita">
                                        <option value=" ">Ingrese Nombre Capita</option>
                                        <option value="COL0421">CARDIOVASCULAR</option>
                                        <option value="COL0446','COL0445">GASTROENTEROLOGIA</option>
                                        <option value="COL0431">NEUMOLOGIA</option>
                                        <option value="COL0435','COL0437">NEUROLOGIA</option>
                                        <option value="COL0423','COL0424">UROLOGIA</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="numeroFactura">Numero Factura</label>
                                    <input type="text" class="form-control" id="numeroFactura" name="numeroFactura" placeholder="Numero Factura" autofocus>
                                </div>
                                <div class="col-sm-2">
                                    <label for="valorFactura">Valor Factura</label>
                                    <input type="text" class="form-control" id="valorFactura" name="valorFactura" placeholder="Valor Factura" autofocus>
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_field">Fecha Inicio:</label>
                                    <input type="date" name="inicio" id="date_field" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <label for="date_field">Fecha Final:</label>
                                    <input type="date" name="fin" id="date_field" class="form-control">
                                </div>
                            </div>

                            <div class="row justify-content-center my-1">
                                <div class="col-2 text-right">
                                    <input type="submit" value="DESCARGAR" class="btn btn-sm" style="background:#0EA0A7; color:white;">
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
<script src="//code.jquery.com/jquery-3.5.1.js"></script>
<script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#onload').fadeOut();
        $('#tablaEgresosUci').removeClass('hidden');
        $('#tablaEgresosUci').DataTable({
            responsive: true,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'csv',
                    text: 'Exportar a CSV',
                    filename: function() {
                        var nombreArchivo = $('#nombreArchivo').data('nombre-archivo');
                        return nombreArchivo;
                    }
                },
                {
                    extend: 'excel',
                    text: 'Exportar a Excel',
                    filename: function() {
                        var nombreArchivo = $('#nombreArchivo').data('nombre-archivo');
                        return nombreArchivo;
                    }
                }
            ]
        });
    });
</script>
@endsection