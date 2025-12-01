@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Apartados Pendientes')
@push('styles')
<link href="{{ asset('css/apartadosPendientes.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush
@section('content')
    <div class="apartados-pendientes-container">
        <div class="apartados-pendientes-max-width">
            <!-- Header -->
            <div class="apartados-pendientes-header">
                <div class="apartados-pendientes-header-content">
                    <h1 class="apartados-pendientes-title">
                        Apartados Pendientes
                    </h1>
                    <div class="apartados-pendientes-stats">
                        <div class="apartados-pendientes-stat-card">
                            <span class="material-icons apartados-pendientes-stat-icon">inventory_2</span>
                            <div class="apartados-pendientes-stat-content">
                                <span class="apartados-pendientes-stat-number">{{ $totalSolicitudes }}</span>
                                <span class="apartados-pendientes-stat-label">Total de solicitudes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            @if (session('success'))
                <div class="apartados-pendientes-alert apartados-pendientes-alert-success">
                    <span class="material-icons apartados-pendientes-alert-icon">check_circle</span>
                    <div class="apartados-pendientes-alert-content">
                        <h4 class="apartados-pendientes-alert-title">Éxito</h4>
                        <p class="apartados-pendientes-alert-message">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="apartados-pendientes-alert apartados-pendientes-alert-error">
                    <span class="material-icons apartados-pendientes-alert-icon">error</span>
                    <div class="apartados-pendientes-alert-content">
                        <h4 class="apartados-pendientes-alert-title">Error</h4>
                        <p class="apartados-pendientes-alert-message">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Tabla -->
            <div class="apartados-pendientes-card">
                <div class="apartados-pendientes-card-content">
                    @if($apartados->count() > 0)
                        <div class="apartados-pendientes-table-container">
                            <table class="apartados-pendientes-table">
                                <thead class="apartados-pendientes-table-header">
                                    <tr>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">tag</span>
                                                ID
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">person</span>
                                                Cliente
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">support_agent</span>
                                                Asesor
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">apartment</span>
                                                Fraccionamiento
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">map</span>
                                                Lotes
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">payments</span>
                                                Monto
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">event</span>
                                                Fecha de Apartado
                                            </span>
                                        </th>
                                        <th class="apartados-pendientes-th">
                                            <span class="apartados-pendientes-th-content">
                                                <span class="material-icons apartados-pendientes-th-icon">settings</span>
                                                Acciones
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="apartados-pendientes-table-body">
                                    @foreach ($apartados as $apartado)
                                        <tr class="apartados-pendientes-tr">
                                            <td class="apartados-pendientes-td">
                                                <span class="apartados-pendientes-id">#{{ $apartado->id_apartado }}</span>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <div class="apartados-pendientes-client-info">
                                                    <span class="apartados-pendientes-client-name">
                                                        {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <div class="apartados-pendientes-asesor-info">
                                                    <span class="apartados-pendientes-asesor-name">
                                                        {{ $apartado->usuario->nombre ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <span class="apartados-pendientes-fraccionamiento">
                                                    {{ $apartado->lotesApartados->first()->lote->fraccionamiento->nombre ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <div class="apartados-pendientes-lotes">
                                                    @foreach($apartado->lotesApartados->pluck('lote.numeroLote') as $lote)
                                                        <span class="apartados-pendientes-lote-badge">{{ $lote }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <span class="apartados-pendientes-amount">
                                                    ${{ number_format($apartado->deposito->cantidad, 2) }}
                                                </span>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <span class="apartados-pendientes-date">
                                                    {{ \Carbon\Carbon::parse($apartado->fechaApartado)->format('d/m/Y H:i') }}
                                                </span>
                                            </td>
                                            <td class="apartados-pendientes-td">
                                                <a href="{{ route('admin.apartados-pendientes.show', $apartado->id_apartado) }}"
                                                   class="apartados-pendientes-btn apartados-pendientes-btn-primary">
                                                    <span class="material-icons apartados-pendientes-btn-icon">visibility</span>
                                                    Ver 
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="apartados-pendientes-empty-state">
                            <span class="material-icons apartados-pendientes-empty-icon">inventory_2</span>
                            <h3 class="apartados-pendientes-empty-title">No hay apartados pendientes</h3>
                            <p class="apartados-pendientes-empty-message">
                                No se encontraron solicitudes de apartado pendientes de revisión.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection