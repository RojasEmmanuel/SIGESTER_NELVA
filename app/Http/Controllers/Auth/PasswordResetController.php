<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Usuario;
use App\Mail\SecurePasswordResetMail;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    protected $codeExpiresMinutes = 10;
    protected $maxAttempts = 3;

    // 1. Mostrar formulario para solicitar código
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    // 2. Enviar código al correo
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'usuario_nombre' => 'required|string|max:50'
        ]);

        $key = 'reset_attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Demasiados intentos. Espera {$seconds} segundos."
            ]);
        }

        $user = Usuario::where('email', $request->email)
                       ->where('usuario_nombre', $request->usuario_nombre)
                       ->where('estatus', 1)
                       ->first();

        $message = 'Si los datos son correctos, recibirás un código de recuperación en tu correo.';

        if ($user) {
            $plainToken = bin2hex(random_bytes(32));
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $plainToken,
                    'code' => $code,
                    'expires_at' => Carbon::now()->addMinutes($this->codeExpiresMinutes),
                    'created_at' => now(),
                ]
            );


             DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->where('expires_at', '<', now())
            ->delete();

            try {
                Mail::to($user->email)->send(new SecurePasswordResetMail($user, $code, $plainToken));
            } catch (\Exception $e) {
                Log::error('Error envío recuperación: ' . $e->getMessage());
                RateLimiter::hit($key, 300);
                return back()->withErrors(['email' => 'Error al enviar el código.']);
            }
        }

        RateLimiter::hit($key, 120);

        return back()->with('status', $message);
       
    }

    // 3. Mostrar formulario de nueva contraseña
    public function showResetForm(Request $request, $token = null)
    {
        $token = $token ?? $request->query('token');

        if (!$token) {
            abort(403, 'Token de recuperación no válido.');
        }

        return view('auth.passwords.reset', compact('token'));
    }

    public function reset(Request $request, $token = null)
    {
        Log::info('TOKEN RECIBIDO EN RESET:', ['token' => $token]);

        $request->validate([
            'code'                 => 'required|string',
            'password'             => 'required|min:8|confirmed',
            'password_confirmation'=> 'required',
        ]);

        if (!$token) {
            return back()->withErrors(['code' => 'Token no proporcionado.']);
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        Log::info('RESET RECORD ENCONTRADO', [
            'encontrado' => $resetRecord ? true : false,
            'email'      => $resetRecord->email ?? 'N/A',
            'code_db'    => $resetRecord->code ?? null,
        ]);

        if (!$resetRecord) {
            return back()->withErrors(['code' => 'El enlace es inválido o ha expirado.']);
        }

        $codigoLimpio = preg_replace('/\D/', '', $request->code);

        if (strlen($codigoLimpio) !== 6 || $codigoLimpio !== str_pad($resetRecord->code, 6, '0', STR_PAD_LEFT)) {
            return back()->withErrors(['code' => 'Código de verificación incorrecto.']);
        }

        $user = Usuario::where('email', $resetRecord->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $resetRecord->email)
            ->delete();

        RateLimiter::clear('reset_attempts:' . $request->ip());

        return redirect()->route('login')
            ->with('success', '¡Contraseña cambiada con éxito! Ya puedes iniciar sesión.');
    }
}