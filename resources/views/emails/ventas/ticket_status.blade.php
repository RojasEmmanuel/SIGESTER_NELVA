@extends('emails.layouts.app')

@section('content')
<!-- Contenido Principal -->
<table width="100%" cellpadding="0" cellspacing="0" style="background: #ffffff;">
    <tr>
        <td class="mobile-padding" style="padding: 40px 30px;">
            
            <!-- Header con degradado -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px; background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%); border-radius: 12px; padding: 20px;">
                <tr>
                    <td>
                        <h2 style="color: #ffffff; margin: 0 0 8px; font-size: 20px; font-weight: 700; font-family: 'Arial', sans-serif;">
                            📋 Notificación - Ticket de Enganche
                        </h2>
                        <p style="margin: 0; font-size: 14px; color: #e2e8f0; font-weight: 400;">
                            Venta #{{ $venta->id_venta }}
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Saludo -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td>
                        <p style="margin: 0 0 12px; font-size: 16px; color: #2d3748; font-weight: 500;">
                            Hola <strong style="color: #1a365d;">{{ $asesor->nombre }} {{ $asesor->apellidos }}</strong>,
                        </p>
                        <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.6;">
                            Se ha actualizado el estado del ticket de enganche de una venta asignada a tu portafolio.
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Tarjeta de Información del Cliente -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="display: inline-block; background: #1a365d; padding: 8px 12px; border-radius: 6px; margin-bottom: 12px;">
                                <p style="margin: 0; font-size: 12px; color: #ffffff; font-weight: 600; letter-spacing: 0.5px;">
                                    👤 CLIENTE ASIGNADO
                                </p>
                            </div>
                            <p style="margin: 8px 0 4px; font-size: 16px; color: #2d3748; font-weight: 600;">
                                {{ $cliente->nombres }} {{ $cliente->apellidos }}
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #718096;">
                                Referencia: Venta #{{ $venta->id }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Estado - Tarjeta Dinámica -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                <tr>
                    <td>
                        @if($ticketEstatus === 'aceptado')
                        <div style="background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%); padding: 24px; border-radius: 12px; border: 2px solid #38a169; box-shadow: 0 4px 12px rgba(56, 161, 105, 0.15);">
                            <div style="display: flex; align-items: center; margin-bottom: 12px;">
                                <div style="background: #38a169; padding: 8px; border-radius: 8px; margin-right: 12px;">
                                    <span style="color: white; font-size: 16px;">✅</span>
                                </div>
                                <p style="margin: 0; font-size: 16px; color: #22543d; font-weight: 700;">
                                    Estado: Aceptado
                                </p>
                            </div>
                            <p style="margin: 0; font-size: 14px; color: #22543d; line-height: 1.6; padding-left: 44px;">
                                El ticket de enganche ha sido aprobado. La venta avanza al estatus de pagos programados.
                            </p>
                        </div>
                        @elseif($ticketEstatus === 'rechazado')
                        <div style="background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); padding: 24px; border-radius: 12px; border: 2px solid #e53e3e; box-shadow: 0 4px 12px rgba(229, 62, 62, 0.15);">
                            <div style="display: flex; align-items: center; margin-bottom: 12px;">
                                <div style="background: #e53e3e; padding: 8px; border-radius: 8px; margin-right: 12px;">
                                    <span style="color: white; font-size: 16px;">❌</span>
                                </div>
                                <p style="margin: 0; font-size: 16px; color: #742a2a; font-weight: 700;">
                                    Estado: Rechazado
                                </p>
                            </div>
                            <p style="margin: 0; font-size: 14px; color: #742a2a; line-height: 1.6; padding-left: 44px;">
                                El ticket de enganche ha sido rechazado. Contacte al cliente para revisar los detalles.
                            </p>
                        </div>
                        @else
                        <div style="background: linear-gradient(135deg, #fffaf0 0%, #feebc8 100%); padding: 24px; border-radius: 12px; border: 2px solid #d69e2e; box-shadow: 0 4px 12px rgba(214, 158, 46, 0.15);">
                            <div style="display: flex; align-items: center; margin-bottom: 12px;">
                                <div style="background: #d69e2e; padding: 8px; border-radius: 8px; margin-right: 12px;">
                                    <span style="color: white; font-size: 16px;">⏳</span>
                                </div>
                                <p style="margin: 0; font-size: 16px; color: #744210; font-weight: 700;">
                                    Estado: En Revisión
                                </p>
                            </div>
                            <p style="margin: 0; font-size: 14px; color: #744210; line-height: 1.6; padding-left: 44px;">
                                El ticket está en proceso de revisión. Tiempo estimado: 24-48 horas hábiles.
                            </p>
                        </div>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Tarjeta de Acciones Requeridas -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div style="background: #f8fafc; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                                <div style="background: #1a365d; padding: 6px 10px; border-radius: 6px; margin-right: 10px;">
                                    <span style="color: white; font-size: 14px;">🎯</span>
                                </div>
                                <p style="margin: 0; font-size: 15px; color: #2d3748; font-weight: 700;">
                                    Acción Requerida
                                </p>
                            </div>
                            
                            @if($ticketEstatus === 'aceptado')
                            <div style="background: #e6fffa; padding: 16px; border-radius: 8px; border-left: 4px solid #38a169; margin-bottom: 12px;">
                                <p style="margin: 0; font-size: 14px; color: #22543d; font-weight: 500;">
                                    Proceda con el seguimiento del cliente para los pagos programados.
                                </p>
                            </div>
                            @elseif($ticketEstatus === 'rechazado')
                            <div style="background: #fed7d7; padding: 16px; border-radius: 8px; border-left: 4px solid #e53e3e; margin-bottom: 12px;">
                                <p style="margin: 0; font-size: 14px; color: #742a2a; font-weight: 600;">
                                    🚨 Contacte al cliente inmediatamente para resolver el problema con el enganche.
                                </p>
                            </div>
                            @else
                            <div style="background: #feebc8; padding: 16px; border-radius: 8px; border-left: 4px solid #d69e2e; margin-bottom: 12px;">
                                <p style="margin: 0; font-size: 14px; color: #744210; font-weight: 500;">
                                    ⏰ Manténgase en espera del resultado de la revisión del ticket.
                                </p>
                            </div>
                            @endif
                            
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 16px;">
                                <p style="margin: 0; font-size: 12px; color: #718096; text-align: center; font-style: italic;">
                                    Notificación generada el {{ now()->format('d/m/Y \\a \\l\\a\\s H:i') }}
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
@endsection