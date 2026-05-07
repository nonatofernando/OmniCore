<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        try {
            $usuario_id = $request->usuario_id ?? Session::get('id');

            if (!$usuario_id) {
                return response()->json([
                    'status' => 'erro',
                    'mensagem' => 'Não autorizado.'
                ], 401);
            }

            $query = Produto::with('categoria_dados')
                ->where('usuario_id', $usuario_id);

            if ($request->filled('categoria_id')) {
                $query->where('categoria_id', $request->categoria_id);
            }

            if ($request->filled('busca')) {
                $busca = trim($request->busca);

                $query->where(function ($q) use ($busca) {
                    $q->where('nome', 'LIKE', "%{$busca}%")
                      ->orWhere('descricao', 'LIKE', "%{$busca}%");
                });
            }

            $produtos = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'sucesso',
                'produtos' => $produtos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Erro ao buscar produtos.',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    public function salvar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'custo' => 'nullable|numeric|min:0',
            'estoque' => 'nullable|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'estoque_maximo' => 'nullable|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'status' => 'nullable|in:ativo,inativo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Dados inválidos.',
                'erros' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $usuario_id = $request->usuario_id ?? Session::get('id');

            if (!$usuario_id) {
                return response()->json([
                    'status' => 'erro',
                    'mensagem' => 'Usuário não autenticado.'
                ], 401);
            }

            $produto = Produto::create([
                'usuario_id'     => (int) $usuario_id,
                'nome'           => trim($request->nome),
                'descricao'      => $request->descricao,
                'estoque_minimo' => (int) ($request->estoque_minimo ?? 0),
                'estoque_maximo' => $request->estoque_maximo,
                'preco'          => (float) $request->preco,
                'custo'          => $request->custo !== null ? (float) $request->custo : null,
                'estoque'        => (int) ($request->estoque ?? 0),
                'categoria_id'   => $request->categoria_id,
                'imagem_url'     => $request->imagem_url,
                'status'         => $request->status ?? 'ativo',
                'vendidos'       => 0
            ]);

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Produto {$produto->nome} cadastrado com sucesso!",
                'produto' => $produto
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Erro ao cadastrar produto.',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    public function detalhes(Request $request, $id)
    {
        try {
            $usuario_id = $request->usuario_id ?? Session::get('id');

            $produto = Produto::with('categoria_dados')
                ->where('usuario_id', $usuario_id)
                ->findOrFail($id);

            return response()->json([
                'status' => 'sucesso',
                'produto' => $produto
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Produto não encontrado.'
            ], 404);
        }
    }

    public function atualizar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'custo' => 'nullable|numeric|min:0',
            'estoque' => 'nullable|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'estoque_maximo' => 'nullable|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'status' => 'nullable|in:ativo,inativo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Dados inválidos.',
                'erros' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $usuario_id = $request->usuario_id ?? Session::get('id');

            $produto = Produto::where('usuario_id', $usuario_id)
                ->findOrFail($id);

            $produto->update([
                'nome'           => trim($request->nome),
                'descricao'      => $request->descricao,
                'estoque_minimo' => (int) ($request->estoque_minimo ?? 0),
                'estoque_maximo' => $request->estoque_maximo,
                'preco'          => (float) $request->preco,
                'custo'          => $request->custo !== null ? (float) $request->custo : null,
                'estoque'        => (int) ($request->estoque ?? 0),
                'categoria_id'   => $request->categoria_id,
                'imagem_url'     => $request->imagem_url,
                'status'         => $request->status ?? 'ativo',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Produto {$produto->nome} atualizado com sucesso!",
                'produto' => $produto
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Erro ao atualizar produto.',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    public function excluir(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $usuario_id = $request->usuario_id ?? Session::get('id');

            $produto = Produto::where('usuario_id', $usuario_id)
                ->findOrFail($id);

            $produto->delete();

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Produto removido com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Erro ao excluir produto.',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    public function getcategorias(Request $request)
    {
        try {
            $usuario_id = $request->usuario_id ?? Session::get('id');

            if (!$usuario_id) {
                return response()->json([
                    'status' => 'erro',
                    'mensagem' => 'Não autorizado.'
                ], 401);
            }

            $categorias = Categoria::where(function ($query) use ($usuario_id) {
                    $query->where('usuario_id', $usuario_id)
                          ->orWhereNull('usuario_id');
                })
                ->orderBy('nome', 'asc')
                ->get([
                    'id',
                    'nome',
                    'descricao',
                    'cor'
                ]);

            return response()->json([
                'status' => 'sucesso',
                'categorias' => $categorias
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Erro ao buscar categorias.',
                'erro' => $e->getMessage()
            ], 500);
        }
    }
}