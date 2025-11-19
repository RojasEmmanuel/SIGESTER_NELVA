<!-- resources/views/emails/secure_password_reset.blade.php -->
<div style="font-family: system-ui, sans-serif; max-width: 600px; margin: 0 auto;">
    <h1 style="color: #1f2937;">Código de Recuperación</h1>
    
    <div style="background: #f3f4f6; padding: 24px; border-radius: 12px; text-align: center;">
        <p style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #111827;">
            {{ chunk_split($code, 3, ' ') }}
        </p>
        <p style="color: #6b7280;">
            Este código expira en <strong>10 minutos</strong>
        </p>
    </div>

    <p style="margin: 24px 0;">
        O haz clic aquí para cambiar tu contraseña directamente:<br>
        <a href="{{ $resetUrl }}" style="color: #3b82f6; text-decoration: underline;">
            {{ $resetUrl }}
        </a>
    </p>

    <hr style="margin: 32px 0; border: 1px solid #e5e7eb;">

    <small style="color: #6b7280;">
        Si no solicitaste este cambio, ignora este mensaje. 
        Tu contraseña no será modificada.
    </small>
</div>