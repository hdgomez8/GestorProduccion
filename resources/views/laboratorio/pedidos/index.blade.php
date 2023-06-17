@extends('layouts.main', ['activePage' => 'laboratorioPedidos', 'titlePage' => 'Pedidos De Laboratorio'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Pedidos</h4>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table display compact" style="width:100%">
                                            <thead class="text-primary">
                                                <th>Codigo Pedido</th>
                                                <th>Insumo</th>
                                                <th>Cantidad</th>
                                                <th>Total</th>
                                                <th>Estado Pedido</th>
                                                <th>Usuario</th>
                                                <th class="text-right">Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($pedidos as $pedido)
                                                    <tr>
                                                        <td>{{ $pedido->codigo }}</td>
                                                        <td>{{ $pedido->producto }}</td>
                                                        <td>{{ $pedido->cantidad }}</td>
                                                        <td>{{ $pedido->total }}</td>
                                                        <td>{{ $pedido->estado }}</td>
                                                        <td>{{ $pedido->user_id }}</td>
                                                        <td class="td-actions text-right">
                                                            @can('compra_show')
                                                                <a href="#" class="btn btn-info"><i
                                                                        class="material-icons">person</i></a>
                                                            @endcan
                                                            @can('compra_edit')
                                                                <a href="#" class="btn btn-warning"><i
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
