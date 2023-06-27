<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>{{ __('Hosvital Support') }}</title> --}}
    <title>{{ __('Gestor De Procesos') }}</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/ico" href="{{ asset('img/favicon.ico') }}">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('css/material-dashboard.css?v=2.1.1') }}" rel="stylesheet" />
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- {{-- <style>
        .table.dataTable {
            font-weight: bold;
            font-size: 10px;
            padding: 2px;
        }
        .table.dataTable tbody {
            color: rgb(236, 26, 26);
            font-size: 10px;
            padding: 2px;
        }
        .table.dataTable th {
            color: black;
            font-size: 10px;
            padding: 2px;
        }
    </style> --}} -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

</head>

<body class="{{ $class ?? '' }}">
    @auth()
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.page_templates.auth')
    @endauth

    @guest()
    @include('layouts.page_templates.guest')
    @endguest
    <!--   Core JS Files   -->
    <script src="{{ asset('js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap-material-design.min.js') }}"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
    {{-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <!-- <script>
        $(document).ready(function() {
            $('#tablaUsuarios').DataTable({
                "createdRow": function(row, data, index) {
                    if (data[2] == "PENDIENTE") {
                        $('td', row).css({
                            'background-color': '#F0A693',
                            'color': 'black',
                            'border-style': '',
                            'border-color': '#aaa196'
                        })
                    }
                    if (data[2] == "EN TRAMITE") {
                        $('td', row).css({
                            'background-color': '#FFFFAD',
                            'color': 'black',
                            'border-style': '',
                            'border-color': '#aaa196'
                        })
                    }
                    if (data[2] == "COMPRADO") {
                        $('td', row).css({
                            'background-color': '#BDECB6',
                            'color': 'black',
                            'border-style': '',
                            'border-color': '#aaa196'
                        })
                    }

                },
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
            });
        });
    </script> -->
    {{-- <script src="{{ asset('js/plugins/perfect-scrollbar.jquery.min.js') }}"></script> --}}
    @stack('js')
    @yield('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

</body>

</html>