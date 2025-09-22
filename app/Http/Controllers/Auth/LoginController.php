<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Muestra la vista del formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Maneja la solicitud de autenticación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario_nombre' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Usuario::where('usuario_nombre', $credentials['usuario_nombre'])->first();

        if (!$user) {
            return back()->withErrors([
                'usuario_nombre' => 'Las credenciales no coinciden.',
            ])->onlyInput('usuario_nombre');
        }

        $passwordValid = false;

        if (Hash::check($credentials['password'], $user->password)) {
            $passwordValid = true;
        } elseif ($credentials['password'] === $user->password) {
            $passwordValid = true;
            $user->password = Hash::make($credentials['password']);
            $user->save();
        }

        if (!$passwordValid) {
            return back()->withErrors([
                'usuario_nombre' => 'Las credenciales no coinciden.',
            ])->onlyInput('usuario_nombre');
        }

        if ($user->estatus != 1) {
            return back()->withErrors([
                'usuario_nombre' => 'Tu cuenta está inactiva.',
            ])->onlyInput('usuario_nombre');
        }

        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();

        $request->session()->flash('success', '¡Bienvenido! Has iniciado sesión correctamente.');

        // Redirigir según el tipo de usuario y enviar el tipo de usuario a la vista
        switch ($user->tipo_usuario) {
            case 1:
                return redirect()->route('admin.dashboard')->with('tipo_usuario', $user->tipo_usuario);
            case 2:
                return redirect()->route('asesor.dashboard')->with('tipo_usuario', $user->tipo_usuario);
            case 3:
                return redirect()->route('cobranza.dashboard')->with('tipo_usuario', $user->tipo_usuario);
            case 4:
                return redirect()->route('ingeniero.dashboard')->with('tipo_usuario', $user->tipo_usuario);
            default:
                return redirect('/dashboard')->with('tipo_usuario', $user->tipo_usuario);
        }
    }
    /**
     * Cierra la sesión del usuario autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Has cerrado sesión correctamente.');
    }
}