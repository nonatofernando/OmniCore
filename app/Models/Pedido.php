<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $fillable = [
        'numero_pedido',
        'cliente_id',
        'total',
        'status',
        'metodo_pagamento',
        'observacoes'
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
            ->withPivot('quantidade', 'preco_unitario')
            ->withTimestamps();
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
