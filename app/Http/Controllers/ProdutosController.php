<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('produtos');
    }

    public function getprodutos(Request $request)
    {
        $usuario_id = $request->id_usuario ?? Session::get('id');

        if (!$usuario_id) {
            return response()->json(['status' => 'erro', 'mensagem' => 'Não autorizado'], 401);
        }

        $query = Produto::with('categoria_dados')->where('usuario_id', $usuario_id);

        if (!empty($request->categoria_id)) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if (!empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%");
            });
        }

        $produtos = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'sucesso',
            'produtos' => $produtos
        ]);
    }

    public function salvar(Request $request)
    {
        try {
            DB::beginTransaction();

            if (empty($request->nome) || empty($request->preco)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'Nome e preço são obrigatórios.'], 400);
            }

            $usuario_id = $request->id_usuario ?? Session::get('id');

            $produto = Produto::create([
                'usuario_id'     => $usuario_id,
                'nome'           => $request->nome,
                'descricao'      => $request->descricao ?? null,
                'estoque_minimo' => $request->estoque_minimo ?? 0,
                'estoque_maximo' => $request->estoque_maximo ?? null,
                'preco'          => $request->preco,
                'custo'          => $request->custo ?? null,
                'estoque'        => $request->estoque ?? 0,
                'categoria_id'   => $request->categoria_id ?? null,
                'imagem_url'     => $request->imagem_url ?? null,
                'status'         => $request->status ?? 'ativo',
                'vendidos'       => 0
            ]);

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Produto {$produto->nome} cadastrado com sucesso!"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function detalhes(Request $request, $id)
    {
        try {
            $usuario_id = $request->id_usuario ?? Session::get('id');
            $produto = Produto::with('categoria_dados')->where('usuario_id', $usuario_id)->findOrFail($id);
            return response()->json(['status' => 'sucesso', 'produto' => $produto]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'erro', 'mensagem' => 'Produto não encontrado.'], 404);
        }
    }

    public function atualizar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            if (empty($request->nome) || empty($request->preco)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'Nome e preço não podem ficar em branco.'], 400);
            }

            $usuario_id = $request->id_usuario ?? Session::get('id');
            $produto = Produto::where('usuario_id', $usuario_id)->findOrFail($id);

            $produto->update([
                'nome'           => $request->nome,
                'descricao'      => $request->descricao ?? $produto->descricao,
                'estoque_minimo' => $request->estoque_minimo ?? $produto->estoque_minimo,
                'estoque_maximo' => $request->estoque_maximo ?? $produto->estoque_maximo,
                'preco'          => $request->preco,
                'custo'          => $request->custo ?? $produto->custo,
                'estoque'        => $request->estoque ?? $produto->estoque,
                'categoria_id'   => $request->categoria_id ?? $produto->categoria_id,
                'imagem_url'     => $request->imagem_url ?? $produto->imagem_url,
                'status'         => $request->status ?? $produto->status,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Produto {$produto->nome} atualizado!"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
        }
    }

    public function excluir(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $usuario_id = $request->id_usuario ?? Session::get('id');
            $produto = Produto::where('usuario_id', $usuario_id)->findOrFail($id);

            $produto->delete();

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Produto removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }

    public function getcategorias(Request $request)
    {
        $usuario_id = $request->id_usuario ?? Session::get('id');

        if (!$usuario_id) {
            return response()->json(['status' => 'erro', 'mensagem' => 'Não autorizado'], 401);
        }

        // Busca as categorias do usuário logado OU categorias globais (usuario_id nulo)
        $categorias = Categoria::where(function ($query) use ($usuario_id) {
                $query->where('usuario_id', $usuario_id)
                      ->orWhereNull('usuario_id');
            })
            ->orderBy('nome', 'asc')
            ->get(['id', 'nome', 'descricao', 'cor']);

        return response()->json([
            'status' => 'sucesso',
            'categorias' => $categorias
        ]);
    }
}