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
                            Notificación - Estado de Venta
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
                            Hola <strong style="color: #1a365d;">{{ $asesor->nombre }} {{ $asesor->apellidos }}</strong>
                        </p>
                        <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.6;">
                            Se ha actualizado el estado de una venta asignada a tu portafolio.
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Tarjeta de Información del Cliente -->
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
                                                    CLIENTE ASIGNADO
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="margin: 8px 0 4px; font-size: 16px; color: #2d3748; font-weight: 600;">
                                                    {{ $cliente->nombres }} {{ $cliente->apellidos }}
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #718096;">
                                                    Referencia: Venta #{{ $venta->id_venta }}
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

            <!-- Estado - Tarjeta Dinámica -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                <tr>
                    <td>
                        @if($ventaEstatus === 'liquidado')
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f0fff4; border-radius: 4px; border-left: 4px solid #38a169; box-shadow: 0 2px 4px rgba(56, 161, 105, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="32" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #38a169; border-radius: 50%; width: 32px; height: 32px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 16px; font-weight: bold; vertical-align: middle;">
                                                            ✓
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #22543d; font-weight: 700;">
                                                    Estado: Liquidado
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #22543d; line-height: 1.6;">
                                                    La venta ha sido liquidada completamente. Proceda con la documentación final.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @elseif($ventaEstatus === 'retraso')
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #fff5f5; border-radius: 4px; border-left: 4px solid #e53e3e; box-shadow: 0 2px 4px rgba(229, 62, 62, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="32" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #e53e3e; border-radius: 50%; width: 32px; height: 32px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 16px; font-weight: bold; vertical-align: middle;">
                                                            !
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #742a2a; font-weight: 700;">
                                                    Estado: Retraso en Pagos
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #742a2a; line-height: 1.6;">
                                                    Se ha detectado un retraso en los pagos. Contacte al cliente urgentemente.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @elseif($ventaEstatus === 'cancelado')
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f9fa; border-radius: 4px; border-left: 4px solid #718096; box-shadow: 0 2px 4px rgba(113, 128, 150, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="32" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #718096; border-radius: 50%; width: 32px; height: 32px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 16px; font-weight: bold; vertical-align: middle;">
                                                            ✕
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #4a5568; font-weight: 700;">
                                                    Estado: Cancelado
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #4a5568; line-height: 1.6;">
                                                    La venta ha sido cancelada. Los lotes han sido liberados.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @else
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #ebf8ff; border-radius: 4px; border-left: 4px solid #3182ce; box-shadow: 0 2px 4px rgba(49, 130, 206, 0.2);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="32" style="padding-right: 12px; vertical-align: top;">
                                                <table cellpadding="0" cellspacing="0" style="background: #3182ce; border-radius: 50%; width: 32px; height: 32px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 16px; font-weight: bold; vertical-align: middle;">
                                                            ↻
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <p style="margin: 0 0 8px; font-size: 16px; color: #2c5282; font-weight: 700;">
                                                    Estado: En Proceso
                                                </p>
                                                <p style="margin: 0; font-size: 14px; color: #2c5282; line-height: 1.6;">
                                                    La venta continúa en proceso. Su cliente se regularizo con los pagos.
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

            <!-- Tarjeta de Acciones Requeridas -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8fafc; border-radius: 4px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                            <tr>
                                <td style="padding: 24px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="40" style="padding-right: 10px; vertical-align: middle;">
                                                <table cellpadding="0" cellspacing="0" style="background: #1a365d; border-radius: 50%; width: 32px; height: 32px; text-align: center;">
                                                    <tr>
                                                        <td style="color: white; font-size: 16px; font-weight: bold; vertical-align: middle;">
                                                            ⚡
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <p style="margin: 0; font-size: 15px; color: #2d3748; font-weight: 700;">
                                                    Acción Requerida
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    @if($ventaEstatus === 'liquidado')
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #e6fffa; border-radius: 4px; border-left: 4px solid #38a169; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #22543d; font-weight: 500;">
                                                    Proceda con el cierre documental de la venta y coordine la entrega final.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @elseif($ventaEstatus === 'retraso')
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #fed7d7; border-radius: 4px; border-left: 4px solid #e53e3e; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #742a2a; font-weight: 600;">
                                                    Contacte al cliente inmediatamente para regularizar los pagos y proteger la inversión.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @elseif($ventaEstatus === 'cancelado')
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #edf2f7; border-radius: 4px; border-left: 4px solid #718096; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #4a5568; font-weight: 500;">
                                                    Verifique la liberación de lotes y actualice el sistema con el estatus de cancelación.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @else
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: #e6fffa; border-radius: 4px; border-left: 4px solid #3182ce; margin-top: 16px;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 14px; color: #2c5282; font-weight: 500;">
                                                    Dé seguimiento regular al estado de la venta y mantenga comunicación con el cliente.
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