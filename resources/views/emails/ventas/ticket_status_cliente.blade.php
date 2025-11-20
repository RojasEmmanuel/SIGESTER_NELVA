@extends('emails.layouts.app')

@section('content')
<!-- Contenido Principal -->
<table width="100%" cellpadding="0" cellspacing="0" style="background: #ffffff;">
    <tr>
        <td class="mobile-padding" style="padding: 40px 30px;">
            
            <!-- Header con Material Design -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px; background: #1a365d; border-radius: 4px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <tr>
                    <td>
                        <h2 style="color: #ffffff; margin: 0 0 8px; font-size: 20px; font-weight: 700; font-family: 'Arial', sans-serif;">
                            Actualización - Ticket de Enganche
                        </h2>
                        <p style="margin: 0; font-size: 14px; color: #e2e8f0; font-weight: 400;">
                            Venta #{{ $venta->id }}
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Saludo al Cliente -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td>
                        <p style="margin: 0 0 12px; font-size: 16px; color: #2d3748; font-weight: 500;">
                            Estimado(a) <strong style="color: #1a365d;">{{ $cliente->nombres }} {{ $cliente->apellidos }}</strong>,
                        </p>
                        <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.6;">
                            Le informamos sobre el estado actual de su ticket de enganche. Agradecemos su confianza en nuestros servicios.
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Tarjeta de Información del Asesor -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8fafc; border-radius: 4px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <tr>
                                <td style="padding: 20px;">
                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="background: #1a365d; padding: 8px 12px; border-radius: 4px; margin-bottom: 12px; display: inline-block;">
                                                <p style="margin: 0; font-size: 12px; color: #ffffff; font-weight: 600; letter-spacing: 0.5px;">
                                                    ASESOR ASIGNADO
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="margin: 8px 0 4px; font-size: 16px; color: #2d3748; font-weight: 600;">
                                                    {{ $asesor->nombre }} {{ $asesor->apellidos }}
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #718096;">
                                                    {{ $asesor->email ?? 'Contacto no disponible' }}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Estado - Tarjeta Dinámica con Material Design Compatible -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                <tr>
                    <td>
                        @if($ticketEstatus === 'aceptado')
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f0fff4; border-radius: 4px; border-left: 4px solid #38a169; box-shadow: 0 2px 4px rgba(56, 161, 105, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="40" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #38a169; border-radius: 50%; width: 40px; height: 40px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 20px; font-weight: bold; vertical-align: middle;">
                                                            ✓
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #22543d; font-weight: 700;">
                                                    Estado: Aceptado
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #22543d; line-height: 1.6;">
                                                    Su ticket de enganche ha sido aprobado. Su compra avanza al estatus de pagos programados.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @elseif($ticketEstatus === 'rechazado')
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #fff5f5; border-radius: 4px; border-left: 4px solid #e53e3e; box-shadow: 0 2px 4px rgba(229, 62, 62, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="40" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #e53e3e; border-radius: 50%; width: 40px; height: 40px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 20px; font-weight: bold; vertical-align: middle;">
                                                            ✕
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #742a2a; font-weight: 700;">
                                                    Estado: Rechazado
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #742a2a; line-height: 1.6;">
                                                    Su ticket de enganche ha sido rechazado. Contacte a su asesor para revisar los detalles.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @else
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #fffaf0; border-radius: 4px; border-left: 4px solid #d69e2e; box-shadow: 0 2px 4px rgba(214, 158, 46, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="40" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #d69e2e; border-radius: 50%; width: 40px; height: 40px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 18px; font-weight: bold; vertical-align: middle;">
                                                            ↻
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #744210; font-weight: 700;">
                                                    Estado: En Revisión
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #744210; line-height: 1.6;">
                                                    Su ticket está en proceso de revisión. Tiempo estimado: 24-48 horas hábiles.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Tarjeta de Siguientes Pasos -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8fafc; border-radius: 4px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="40" style="padding-right: 10px; vertical-align: middle;">
                                                <table cellpadding="0" cellspacing="0" style="background: #1a365d; border-radius: 4px; width: 36px; height: 36px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 16px; font-weight: bold; vertical-align: middle;">
                                                            →
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <p style="margin: 0; font-size: 15px; color: #2d3748; font-weight: 700;">
                                                    Siguientes Pasos
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    @if($ticketEstatus === 'aceptado')
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #e6fffa; border-radius: 4px; border-left: 4px solid #38a169; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #22543d; font-weight: 500;">
                                                    Su asesor se pondrá en contacto con usted para coordinar los pagos programados.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @elseif($ticketEstatus === 'rechazado')
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #fed7d7; border-radius: 4px; border-left: 4px solid #e53e3e; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #742a2a; font-weight: 600;">
                                                    Contacte a su asesor asignado para revisar los detalles del ticket.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @else
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #feebc8; border-radius: 4px; border-left: 4px solid #d69e2e; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #744210; font-weight: 500;">
                                                    Recibirá una nueva notificación una vez completada la revisión.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @endif
                                    
                                    <table width="100%" cellpadding="0" cellspacing="0" style="border-top: 1px solid #e2e8f0; margin-top: 16px;">
                                        <tr>
                                            <td style="padding-top: 12px;">
                                                <p style="margin: 0; font-size: 12px; color: #718096; text-align: center; font-style: italic;">
                                                    Notificación generada el {{ now()->format('d/m/Y \\a \\l\\a\\s H:i') }}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
@endsection