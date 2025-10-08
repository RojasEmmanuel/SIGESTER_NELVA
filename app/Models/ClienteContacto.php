<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClienteContacto extends Model
{
    use HasFactory;

    protected $table = 'cliente_contacto';
    protected $primaryKey = 'id_contacto';

    protected $fillable = [
        'telefono',
        'email',
        'id_cliente',
    ];

    public function cliente()
    {
        return $this->belongsTo(ClienteVenta::class, 'id_cliente', 'id_cliente');
    }
    
}
