@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Inicio')

@push('styles')
<link href="{{ asset('css/inicioAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
    <h1>Bienvenido, Administrador</h1>
    <p>Este es el panel de control para los administradores.</p>
@endsection