<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\Usuario;

class SecurePasswordResetMail extends Mailable
{
    /**
     * Number of minutes the reset code is valid.
     *
     * @var int
     */
    protected $codeExpiresMinutes = 10;

    protected $user;
    protected $code;
    protected $resetUrl;

    public function __construct($user, $code, $plainToken)
    {
        $this->user = $user;
        $this->code = $code;
        // Usar la ruta con nombre en lugar de URL directa
        $this->resetUrl = route('password.reset', ['token' => $plainToken]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Código de Recuperación - Expira en 10 minutos',
            tags: ['password-reset'],
            metadata: [
                'user_id' => $this->user->id_usuario,
                'expires_at' => now()->addMinutes(10)->timestamp
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.secure_password_reset',
            with: [
                'user' => $this->user,
                'code' => $this->code,
                'resetUrl' => $this->resetUrl,
                'expires' => $this->codeExpiresMinutes
            ]
        );
    }
}