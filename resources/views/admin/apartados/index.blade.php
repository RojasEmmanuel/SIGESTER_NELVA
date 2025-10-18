<!-- resources/views/admin/apartados/index.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Apartados Pendientes')
@push('styles')
<link href="{{ asset('css/apartadosPendientes.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                        Apartados con Depósito Pendientes
                    </h2>
                    <h3 class="text-lg font-medium">Total de solicitudes: {{ $totalSolicitudes }}</h3>

                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fraccionamiento</th>
                                <th>Lotes</th>
                                <th>Monto</th>
                                <th>Fecha de Apartado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($apartados as $apartado)
                                <tr>
                                    <td>{{ $apartado->id_apartado }}</td>
                                    <td>{{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}</td>
                                    <td>
                                        {{ $apartado->lotesApartados->first()->lote->fraccionamiento->nombre ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $apartado->lotesApartados->pluck('lote.numeroLote')->implode(', ') }}
                                    </td>
                                    <td>{{ number_format($apartado->deposito->cantidad, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($apartado->fechaApartado)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.apartados-pendientes.show', $apartado->id_apartado) }}"
                                           class="btn btn-primary btn-sm">Ver Detalles</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay apartados pendientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection