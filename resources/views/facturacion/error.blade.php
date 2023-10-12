@extends('layouts.main', ['activePage' => 'facturacionError', 'titlePage' => 'Error'])

@section('content')
    <div class="alert alert-danger">
        <strong>Error:</strong> {{ $error }}
    </div>
@endsection
