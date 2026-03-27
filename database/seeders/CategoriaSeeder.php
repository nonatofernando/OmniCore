<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categorias Padrão (Sistema - visíveis para todos)
        $categorias_padrao = [
            ['nome' => 'Alimentos', 'cor' => '#ef4444', 'descricao' => 'Produtos alimentícios em geral'],
            ['nome' => 'Bebidas', 'cor' => '#3b82f6', 'descricao' => 'Sucos, águas, refrigerantes e alcoólicos'],
            ['nome' => 'Higiene', 'cor' => '#10b981', 'descricao' => 'Produtos de limpeza pessoal e cuidados'],
            ['nome' => 'Limpeza', 'cor' => '#f59e0b', 'descricao' => 'Produtos para manutenção e limpeza da casa'],
            ['nome' => 'Eletrônicos', 'cor' => '#8b5cf6', 'descricao' => 'Dispositivos, cabos e acessórios tecnológicos'],
            ['nome' => 'Papelaria', 'cor' => '#ec4899', 'descricao' => 'Materiais de escritório e estudo'],
            ['nome' => 'Vestuário', 'cor' => '#6b7280', 'descricao' => 'Roupas, calçados e acessórios'],
            ['nome' => 'Pet Shop', 'cor' => '#f97316', 'descricao' => 'Rações e cuidados para animais'],
            ['nome' => 'Saúde', 'cor' => '#06b6d4', 'descricao' => 'Medicamentos e itens de primeiros socorros'],
            ['nome' => 'Outros', 'cor' => '#4b5563', 'descricao' => 'Categorias diversas'],
        ];

        foreach ($categorias_padrao as $categoria) {
            DB::table('categorias')->updateOrInsert(
                ['nome' => $categoria['nome'], 'usuario_id' => null],
                [
                    'cor' => $categoria['cor'],
                    'descricao' => $categoria['descricao'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Exemplo de Categorias Personalizadas (apenas se houver usuários)
        $usuario = DB::table('usuarios')->first();
        
        if ($usuario) {
            $categorias_usuario = [
                ['nome' => 'Minha Coleção VIP', 'cor' => '#000000', 'descricao' => 'Itens exclusivos do usuário'],
                ['nome' => 'Estoque Antigo', 'cor' => '#78350f', 'descricao' => 'Produtos de safras passadas'],
            ];

            foreach ($categorias_usuario as $categoria) {
                DB::table('categorias')->updateOrInsert(
                    ['nome' => $categoria['nome'], 'usuario_id' => $usuario->id],
                    [
                        'cor' => $categoria['cor'],
                        'descricao' => $categoria['descricao'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}