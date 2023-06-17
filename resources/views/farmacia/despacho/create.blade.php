@extends('layouts.main', ['activePage' => 'laboratorioPedidos', 'titlePage' => 'Crear Pedidos'])
@section('content')
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            Crear Pedidos
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('adjuntar') == 'Debes adjuntar Algo')
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'No has adjuntado ningun documento',
                text: 'Debes Adjuntar Al Menos Un Documento...!'
            })
        </script>
    @endif
@endsection
