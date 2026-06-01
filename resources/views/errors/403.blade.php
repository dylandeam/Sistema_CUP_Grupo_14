@extends('adminlte::page')

@section('title', '403 - Acceso no autorizado')

@section('content_header')
    <center>
        <h1 class="text-danger"><b>403 - Acceso no autorizado</b></h1>
    </center> 
    <hr>
@stop

@section('content')
<div class="text-center">
    <i class="fas fa-ban fa-4x text-danger mb-3"></i>
    <h3>No tiene permiso para acceder a esta página.</h3>
    <a href="{{ url('/home') }}" class="btn btn-primary mt-3">Regresar</a>
</div>
@stop
