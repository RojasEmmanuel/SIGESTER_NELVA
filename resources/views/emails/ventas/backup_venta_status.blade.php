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
                            Actualización - Estado de Compra
                        </h2>
                        <p style="margin: 0; font-size: 14px; color: #666666;">
                            Venta #{{ $venta->id_venta }}
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
                        @if($ventaEstatus === 'liquidado')
                        <div style="background: #f0fff4; padding: 15px; border-left: 4px solid #38a169;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #22543d; font-weight: bold;">
                                ✅ Estado: Liquidado
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #22543d;">
                                Su propiedad ha sido liquidada completamente. Próximamente coordinaremos la documentación final.
                            </p>
                        </div>
                        @elseif($ventaEstatus === 'retraso')
                        <div style="background: #fff5f5; padding: 15px; border-left: 4px solid #e53e3e;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #742a2a; font-weight: bold;">
                                🚨 Estado: Retraso
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #742a2a;">
                                Se detectó retraso en pagos. Contacte urgentemente a su asesor para regularizar.
                            </p>
                        </div>
                        @elseif($ventaEstatus === 'cancelado')
                        <div style="background: #f8f9fa; padding: 15px; border-left: 4px solid #718096;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #4a5568; font-weight: bold;">
                                ❌ Estado: Cancelado
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #4a5568;">
                                La venta ha sido cancelada. Los lotes han sido liberados.
                            </p>
                        </div>
                        @else
                        <div style="background: #ebf8ff; padding: 15px; border-left: 4px solid #3182ce;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #2c5282; font-weight: bold;">
                                🔄 Estado: En Pagos
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #2c5282;">
                                Su compra vuelve a estar en pagos. Gracias por estar al corriente con sus pagos.
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
                                Asesor de Soporte
                            </p>
                            <p style="margin: 0 0 5px; font-size: 14px; color: #333333;">
                                {{ $asesor->nombre }} {{ $asesor->apellidos }}
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #666666;">
                                {{ $asesor->email ?? 'Contacto no disponible' }}
                            </p>
                            @if($ventaEstatus === 'retraso')
                            <p style="margin: 10px 0 0; font-size: 13px; color: #e53e3e; font-weight: 500;">
                                Contacte inmediatamente para proteger su inversión.
                            </p>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
@endsection