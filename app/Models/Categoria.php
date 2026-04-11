<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

    protected $fillable = [
        'usuario_id',
        'nome',
        'descricao',
        'cor'
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'categoria_id');
    }
}
