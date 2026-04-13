<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Define o nome da tabela (opcional, se for o plural padrão)
    protected $table = 'empresas';

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment)
     */
    protected $fillable = [
        'nome',
        'cnpj',
        'endereco',
        'cidade',
        'estado'
    ];

    /**
     * Relacionamento: Uma empresa pode ter muitos usuários
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'empresa_id');
    }
}