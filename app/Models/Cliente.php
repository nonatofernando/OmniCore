<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id',
        'nome',
        'email',
        'telefone',
        'empresa',
        'endereco',
        'observacoes',
        'status'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }
}
