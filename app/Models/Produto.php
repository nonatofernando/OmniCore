<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'usuario_id',
        'nome',
        'descricao',
        'preco',
        'custo',
        'estoque',
        'estoque_minimo',
        'estoque_maximo',
        'categoria_id',
        'sku',
        'imagem_url',
        'status',
        'vendidos',
        'avaliacao'
    ];

    protected $casts = [
        'preco' => 'float',
        'custo' => 'float',
        'estoque' => 'integer',
        'estoque_minimo' => 'integer',
        'estoque_maximo' => 'integer',
        'vendidos' => 'integer',
        'avaliacao' => 'float'
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