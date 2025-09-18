<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Para que sea usado en login
use Illuminate\Notifications\Notifiable;

class Usuario extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

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
