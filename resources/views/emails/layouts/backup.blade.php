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
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #03386c;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    <tr>
                        <td align="center" class="mobile-padding" style="padding: 0 20px;">
                            <img src="/public/images/LogoNegativo.png" alt="Nelva Bienes Raíces" width="150" style="display:block; max-width: 150px; height: auto;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Contenido Principal -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 25px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background: #ffffff; border: 1px solid #dddddd;">
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
            <td align="center" style="padding: 0 0 30px;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">
                    <tr>
                        <td class="mobile-padding" style="padding: 0 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: #ffffff; border: 1px solid #dddddd;">
                                <tr>
                                    <td class="mobile-padding" style="padding: 25px;">
                                        <p style="margin: 0 0 15px; font-size: 14px; color: #666666; line-height: 1.4;">
                                            <strong>Nelva Bienes Raíces</strong><br>
                                            Calle Matamoros, Esquina Abasolo, Frente a CFE<br>
                                            San Pedro Pochutla, Oaxaca<br>
                                            Tel: 958 136 2522 | Email: marketing@nelvabienesraices.com
                                        </p>
                                        <p style="margin: 0; font-size: 12px; color: #888888;">
                                            © {{ date('Y') }} Todos los derechos reservados. Mensaje automático.
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