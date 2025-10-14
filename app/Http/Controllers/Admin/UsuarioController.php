<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\AsesorInfo;
use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Mostrar lista de usuarios (index).
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Usuario::with(['tipo', 'asesorInfo']);

        // --- Filtros ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('usuario_nombre', 'like', "%$search%");
            });
        }
        if ($request->filled('tipo_usuario')) {
            $query->where('tipo_usuario', $request->tipo_usuario);
        }
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }
        if ($request->filled('zona')) {
            $query->join('asesores_info', 'usuarios.id_usuario', '=', 'asesores_info.id_usuario')
                ->where('asesores_info.zona', $request->zona);
        }

        // --- Ordenamiento ---
        $sort = $request->get('sort', 'id_usuario'); // Default por ID
        $direction = $request->get('direction', 'desc'); // Default descendente

        // Validar columna para seguridad (evita SQL injection)
        $validSorts = ['nombre', 'email', 'tipo', 'estatus', 'id_usuario'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'id_usuario';
        }

        if ($sort === 'tipo') {
            $query->join('tipos_usuarios', 'usuarios.tipo_usuario', '=', 'tipos_usuarios.id_tipo')
                ->orderBy('tipos_usuarios.tipo', $direction);
        } elseif ($sort === 'estatus') {
            $query->orderBy('usuarios.estatus', $direction);
        } elseif ($sort === 'nombre') {
            $query->orderBy('usuarios.nombre', $direction);
        } elseif ($sort === 'email') {
            $query->orderBy('usuarios.email', $direction);
        } else {
            $query->orderBy('usuarios.id_usuario', $direction);
        }

        // Seleccionar solo columnas de usuarios para evitar duplicados en joins
        $query->select('usuarios.*');

        $usuarios = $query->paginate(10)->appends($request->query());

        return view('admin.usuarios.index', compact('usuarios'));
    }
    /**
     * Mostrar formulario para crear usuario (create).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $tipos = TipoUsuario::all();
        return view('admin.usuarios.create', compact('tipos'));
    }

    /**
     * Registrar un nuevo usuario (store).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'usuario_nombre' => 'required|string|unique:usuarios,usuario_nombre|max:255',
            'estatus' => 'required|boolean',
            'tipo_usuario' => 'required|integer|exists:tipos_usuarios,id_tipo',
            'zona' => [
                'exclude_if:tipo_usuario,4',  // Excluir si es ingeniero (id_tipo=4)
                'required',
                Rule::in(['costa', 'istmo']),
            ],
            'path_facebook' => 'exclude_if:tipo_usuario,4|nullable|url',
            'path_fotografia' => 'exclude_if:tipo_usuario,4|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'nombre', 'telefono', 'email', 'usuario_nombre', 'estatus', 'tipo_usuario'
        ]);
        $data['password'] = Hash::make($request->password);

        $usuario = Usuario::create($data);

        // Si no es ingeniero (id_tipo != 4), crear registro en asesores_info
        if ($request->tipo_usuario != 4) {
            AsesorInfo::create([
                'zona' => $request->zona,
                'path_facebook' => $request->path_facebook,
                'path_fotografia' => $request->path_fotografia,
                'id_usuario' => $usuario->id_usuario,
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario registrado exitosamente');
    }

    /**
     * Mostrar formulario para editar usuario (edit).
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $usuario = Usuario::with('asesorInfo')->findOrFail($id);
        $tipos = TipoUsuario::all();
        return view('admin.usuarios.edit', compact('usuario', 'tipos'));
    }

    /**
     * Actualizar un usuario existente (update).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Construir reglas dinámicas para 'zona'
        $zonaRules = ['exclude_if:tipo_usuario,4', 'sometimes', 'required'];
        if ($request->filled('tipo_usuario') && $request->tipo_usuario != 4) {
            $zonaRules[] = Rule::in(['costa', 'istmo']);
        } elseif (!$request->has('tipo_usuario') && $usuario->tipo_usuario != 4) {
            $zonaRules[] = Rule::in(['costa', 'istmo']);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'telefono' => 'sometimes|required|string|max:20',
            'email' => ['sometimes', 'required', 'email', Rule::unique('usuarios', 'email')->ignore($usuario->id_usuario, 'id_usuario')],
            'password' => 'sometimes|nullable|string|min:8|confirmed',  // Cambiado a nullable si vacío
            'usuario_nombre' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('usuarios', 'usuario_nombre')->ignore($usuario->id_usuario, 'id_usuario')],
            'estatus' => 'sometimes|required|boolean',
            'tipo_usuario' => 'sometimes|required|integer|exists:tipos_usuarios,id_tipo',
            'zona' => $zonaRules,
            'path_facebook' => 'exclude_if:tipo_usuario,4|sometimes|nullable|url',
            'path_fotografia' => 'exclude_if:tipo_usuario,4|sometimes|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'nombre', 'telefono', 'email', 'usuario_nombre', 'estatus', 'tipo_usuario'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        $nuevoTipo = $request->filled('tipo_usuario') ? $request->tipo_usuario : $usuario->tipo_usuario;

        // Manejar asesores_info solo si no es ingeniero
        if ($nuevoTipo != 4) {
            $asesorData = [
                'zona' => $request->zona,
                'path_facebook' => $request->path_facebook,
                'path_fotografia' => $request->path_fotografia,
            ];

            // Filtrar solo los campos proporcionados (ignorar null/empty si no se envían)
            $asesorData = array_filter($asesorData, fn($value) => $value !== null && $value !== '');

            if (!empty($asesorData) || $request->hasAny(['zona', 'path_facebook', 'path_fotografia'])) {
                $usuario->asesorInfo()->updateOrCreate(['id_usuario' => $usuario->id_usuario], $asesorData);
            }
        } else {
            // Si cambia a ingeniero, eliminar el registro en asesores_info
            if ($usuario->asesorInfo) {
                $usuario->asesorInfo()->delete();
            }
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Inactivar un usuario (cambiar estatus a 0).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inactivate($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['estatus' => 0]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario inactivado exitosamente');
    }

    /**
     * Activar un usuario (cambiar estatus a 1).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['estatus' => 1]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario activado exitosamente');
    }
}