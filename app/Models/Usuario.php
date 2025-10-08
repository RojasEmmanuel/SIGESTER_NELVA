<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    // Especifica el nombre de la columna de contraseña
    protected $password = 'password';
        protected $rememberTokenName = 'remember_token';
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'password',
        'usuario_nombre',
        'estatus',
        'tipo_usuario',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'estatus' => 'boolean',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario', 'id_tipo');
    }
}