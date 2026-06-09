@extends('adminlte::page')

@section('title', 'Pago Completado')

@section('content_header')
    <h1>Pago Completado</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inscripción pagada</h3>
                </div>
                <div class="card-body">
                    <p>El pago de la inscripción se ha completado correctamente.</p>
                    <p>Gracias por su pago en <strong>USD</strong> (dólares estadounidenses).</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Ir al inicio</a>
                </div>
            </div>
        </div>
    </div>
@stop
