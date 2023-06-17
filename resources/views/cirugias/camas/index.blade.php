@extends('layouts.main', ['activePage' => 'compraCirugia', 'titlePage' => 'Compras'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Compras</h4>
                                    {{-- <p class="card-category">Compras Pendientes Por Realizar</p> --}}
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            @can('compra_pendientes')
                                                <a href="{{ route('compras.index') }}" class="btn btn-sm"
                                                    style="background:#F0A693;">Pendientes</a>
                                            @endcan
                                            @can('compra_enTramite')
                                                <a href="{{ route('compras.enTramite') }}" class="btn btn-sm"
                                                    style="background:#FFFFAD;">En Tramite</a>
                                            @endcan
                                            @can('compra_compradas')
                                                <a href="{{ route('compras.comprados') }}" class="btn btn-sm"
                                                    style="background:#BDECB6;">Comprados</a>
                                            @endcan
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table display compact" id="tablaUsuarios" style="width:100%">
                                            <thead class="text-primary">
                                                <th>Fecha Solicitud</th>
                                                <th>Numero Cirugia</th>
                                                <th>Estado Compra</th>
                                                <th>Tipo Documento</th>
                                                <th>Numero Documento</th>
                                                <th>Nombre Paciente</th>
                                                <th>Estado Cirugia</th>
                                                <th>Pabellon</th>
                                                <th class="text-right">Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($compras as $compra)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($compra->ProFSep)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ $compra->ProCirCod }}</td>
                                                        <td>{{ $compra->MatQxAdq }}</td>
                                                        <td>{{ $compra->MPTDoc }}</td>
                                                        <td>{{ $compra->MPCedu }}</td>
                                                        <td>{{ $compra->MPNOMC }}</td>
                                                        <td>{{ $compra->ProEsta }}</td>
                                                        <td>{{ $compra->MPNomP }}</td>
                                                        <td class="td-actions text-right">
                                                            @can('compra_show')
                                                                <a href="{{ route('compras.show', $compra->ProCirCod) }}"
                                                                    class="btn btn-info"><i
                                                                        class="material-icons">person</i></a>
                                                            @endcan
                                                            @can('compra_edit')
                                                                <a href="{{ route('compras.edit', $compra->ProCirCod) }}"
                                                                    class="btn btn-warning"><i
                                                                        class="material-icons">edit</i></a>
                                                            @endcan
                                                            @can('compra_destroy')
                                                                <form action="#" method="POST" style="display: inline-block;"
                                                                    onsubmit="return confirm('Seguro?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger" type="submit" rel="tooltip">
                                                                        <i class="material-icons">close</i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                {{-- <div class="card-footer mr-auto">
                    {{ $users->links() }}
                  </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
