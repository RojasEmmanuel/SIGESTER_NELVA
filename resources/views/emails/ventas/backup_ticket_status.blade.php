@extends('emails.layouts.app')

@section('content')
<!-- Contenido Principal -->
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="mobile-padding" style="padding: 30px;">
            
            <!-- Encabezado -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                <tr>
                    <td>
                        <h2 style="color: #1a365d; margin: 0 0 10px; font-size: 18px; font-weight: bold;">
                            Actualización - Ticket de Enganche
                        </h2>
                        <p style="margin: 0; font-size: 14px; color: #666666;">
                            Venta #{{ $venta->id }}
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Saludo -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                <tr>
                    <td>
                        <p style="margin: 0 0 15px; font-size: 15px; color: #333333;">
                            Estimado(a) <strong>{{ $cliente->nombres }} {{ $cliente->apellidos }}</strong>,
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Estado -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                <tr>
                    <td>
                        @if($ticketEstatus === 'aceptado')
                        <div style="background: #f0fff4; padding: 15px; border-left: 4px solid #38a169;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #22543d; font-weight: bold;">
                                ✅ Estado: Aceptado
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #22543d;">
                                Su ticket de enganche ha sido aprobado. Su compra avanza al estatus de pagos programados.
                            </p>
                        </div>
                        @elseif($ticketEstatus === 'rechazado')
                        <div style="background: #fff5f5; padding: 15px; border-left: 4px solid #e53e3e;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #742a2a; font-weight: bold;">
                                ❌ Estado: Rechazado
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #742a2a;">
                                Su ticket requiere atención. Contacte a su asesor para revisar los detalles.
                            </p>
                        </div>
                        @else
                        <div style="background: #fffaf0; padding: 15px; border-left: 4px solid #d69e2e;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #744210; font-weight: bold;">
                                ⏳ Estado: En Revisión
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #744210;">
                                Su ticket está en revisión. Tiempo estimado: 24-48 horas hábiles.
                            </p>
                        </div>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Asesor -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div style="border-top: 1px solid #e0e0e0; padding-top: 15px;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #333333; font-weight: bold;">
                                Asesor Asignado
                            </p>
                            <p style="margin: 0 0 5px; font-size: 14px; color: #333333;">
                                {{ $asesor->nombre }} {{ $asesor->apellidos }}
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #666666;">
                                {{ $asesor->email ?? 'Contacto no disponible' }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
@endsection