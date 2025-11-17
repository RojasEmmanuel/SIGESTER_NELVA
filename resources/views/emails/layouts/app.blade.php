<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notificación' }}</title>
    <style type="text/css">
        @media only screen and (max-width: 480px) {
            .container { width: 100% !important; }
            .mobile-padding { padding: 20px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f8f9fa; font-family: Arial, sans-serif; line-height: 1.5; color: #333333;">

    <!-- Header Corporativo -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%);">
        <tr>
            <td align="center" style="padding: 25px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    <tr>
                        <td align="center" class="mobile-padding" style="padding: 0 20px;">
                            <img src="/public/images/LogoNegativo.png" alt="Nelva Bienes Raíces" width="180" style="display:block; max-width: 180px; height: auto; margin-bottom: 10px;">
                            <p style="margin: 0; font-size: 14px; color: #e2e8f0; font-style: italic;">
                                Tu patrimonio, nuestra prioridad
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Contenido Principal -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 30px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
                    <tr>
                        <td style="padding: 0;">
                            @yield('content')
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer Corporativo -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 0 0 40px;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    <tr>
                        <td class="mobile-padding" style="padding: 0 20px;">
                            <!-- Footer Principal -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #1a365d; border-radius: 12px 12px 0 0; margin-bottom: 0;">
                                <tr>
                                    <td class="mobile-padding" style="padding: 30px 25px;">
                                        <p style="margin: 0 0 20px; font-size: 16px; color: #ffffff; font-weight: bold; border-bottom: 2px solid #38a169; padding-bottom: 8px; display: inline-block;">
                                            INFORMACIÓN DE CONTACTO
                                        </p>
                                        
                                        <p style="margin: 0 0 15px; font-size: 14px; color: #e2e8f0; line-height: 1.6;">
                                            <strong style="color: #ffffff;">📞 (+52) 958-119-9171</strong>  
                                            <strong style="color: #ffffff;">✉️ marketing@nelvabienesraices.com</strong>
                                        </p>
                                        
                                        <p style="margin: 0 0 20px; font-size: 14px; color: #e2e8f0; line-height: 1.6;">
                                            <strong style="color: #ffffff;">📍</strong> Adolfo Lopez Mateos 16, Loma Larga, 70900 San Pedro Pochutla, Oax.
                                        </p>

                                        <p style="margin: 0 0 15px; font-size: 16px; color: #ffffff; font-weight: bold; border-bottom: 2px solid #3182ce; padding-bottom: 8px; display: inline-block;">
                                            ¡Mantente al día con las últimas novedades!
                                        </p>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Footer Inferior -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #2d3748; border-radius: 0 0 12px 12px;">
                                <tr>
                                    <td align="center" class="mobile-padding" style="padding: 20px 25px;">
                                        <p style="margin: 0; font-size: 12px; color: #a0aec0;">
                                            © {{ date('Y') }} Nelva Bienes Raíces. Todos los derechos reservados. | Mensaje automático generado por el sistema.
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

</body>
</html>