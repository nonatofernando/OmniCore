<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'custo',
        'estoque',
        'categoria_id',
        'sku',
        'imagem_url',
        'status',
        'vendidos',
        'avaliacao'
    ];

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_produto')
            ->withPivot('quantidade', 'preco_unitario')
            ->withTimestamps();
    }

    public function categoria_dados()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
