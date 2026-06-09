@extends('adminlte::page')

@section('title', 'Pago Cancelado')

@section('content_header')
    <h1>Pago Cancelado</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Pago no realizado</h3>
                </div>
                <div class="card-body">
                    <p>El pago no se completó.</p>
                    <p>Si desea intentar de nuevo, regrese al formulario de inscripción y genere nuevamente el enlace de pago.</p>
                    <a href="{{ url('/') }}" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
@stop
