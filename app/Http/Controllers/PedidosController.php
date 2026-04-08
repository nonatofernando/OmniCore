<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\PedidoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }
        return view('pedidos');
    }

    public function getPedidos(Request $request)
    {
        $usuario_id = $request->id_usuario ?? Session::get('id');

        $query = Pedido::with('cliente')->where('usuario_id', $usuario_id);

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        if (!empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('numero_pedido', 'like', "%{$busca}%")
                    ->orWhereHas('cliente', function ($c) use ($busca) {
                        $c->where('nome', 'like', "%{$busca}%");
                    });
            });
        }

        $pedidos = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'resposta' => 'sucesso',
            'pedidos' => $pedidos
        ]);
    }

    public function getDadosIniciais()
    {
        return response()->json([
            'clientes' => Cliente::all(['id', 'nome']),
            'produtos' => Produto::all(['id', 'nome', 'preco', 'estoque'])
        ]);
    }

    public function salvar(Request $request)
    {
        try {
            DB::beginTransaction();

            if (empty($request->id_cliente)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'Cliente é obrigatório.'], 400);
            }

            if (empty($request->lista_produtos) || !is_array($request->lista_produtos)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'Adicione pelo menos um produto ao pedido.'], 400);
            }

            $usuario_id = $request->id_usuario ?? Session::get('id');

            $ultimoPedido = Pedido::orderBy('id', 'desc')->first();
            $numero_pedido = $ultimoPedido ? (int)$ultimoPedido->numero_pedido + 1 : 1;
            $numero_pedido_formatado = str_pad($numero_pedido, 6, '0', STR_PAD_LEFT);

            $total = 0;
            foreach ($request->lista_produtos as $item) {
                if (!empty($item['id_produto']) && !empty($item['qtd_produto'])) {
                    $produto = Produto::find($item['id_produto']);
                    if ($produto) {
                        $total += $produto->preco * $item['qtd_produto'];
                    }
                }
            }

            $pedido = Pedido::create([
                'numero_pedido' => $numero_pedido_formatado,
                'cliente_id' => $request->id_cliente,
                'usuario_id' => $usuario_id,
                'total' => $total,
                'status' => 'pendente',
                'metodo_pagamento' => $request->metodo_pagamento ?? 'pix',
                'observacoes' => $request->obs_pedido ?? null,
            ]);

            foreach ($request->lista_produtos as $item) {
                if (!empty($item['id_produto']) && !empty($item['qtd_produto'])) {
                    $produto = Produto::find($item['id_produto']);
                    if ($produto) {
                        $quantidade = (int)$item['qtd_produto'];
                        PedidoProduto::create([
                            'pedido_id' => $pedido->id,
                            'produto_id' => $produto->id,
                            'quantidade' => $quantidade,
                        ]);

                        $produto->estoque = max(0, $produto->estoque - $quantidade);
                        $produto->vendidos += $quantidade;
                        $produto->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Pedido #{$numero_pedido_formatado} criado com sucesso!"
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

            $pedido = Pedido::with(['produtos', 'cliente'])
                ->where('usuario_id', $usuario_id)
                ->findOrFail($id);

            $pedido_simplificado = [
                'id' => $pedido->id,
                'numero_pedido' => $pedido->numero_pedido,
                'total' => $pedido->total,
                'status' => $pedido->status,
                'metodo_pagamento' => $pedido->metodo_pagamento,
                'observacoes' => $pedido->observacoes,
                'produtos' => $pedido->produtos->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nome' => $p->nome,
                        'preco' => $p->preco,
                        'quantidade' => $p->pivot->quantidade,
                    ];
                }),
                'cliente' => $pedido->cliente ? [
                    'id' => $pedido->cliente->id,
                    'nome' => $pedido->cliente->nome,
                ] : null,
            ];

            return response()->json(['pedido' => $pedido_simplificado]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'erro', 'mensagem' => 'Pedido não encontrado.'], 404);
        }
    }

    public function atualizar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            if (empty($request->id_cliente)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'O cliente não pode ficar em branco.'], 400);
            }

            if (empty($request->lista_produtos) || !is_array($request->lista_produtos)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'O pedido precisa ter pelo menos um produto.'], 400);
            }

            $usuario_id = $request->id_usuario ?? Session::get('id');
            $pedido = Pedido::where('usuario_id', $usuario_id)->findOrFail($id);

            $produtos_antigos = PedidoProduto::where('pedido_id', $pedido->id)->get();
            foreach ($produtos_antigos as $p) {
                $produto = Produto::find($p->produto_id);
                if ($produto) {
                    $produto->estoque += $p->quantidade;
                    $produto->vendidos = max(0, $produto->vendidos - $p->quantidade);
                    $produto->save();
                }
            }

            PedidoProduto::where('pedido_id', $pedido->id)->delete();

            $total = 0;
            foreach ($request->lista_produtos as $item) {
                if (!empty($item['id_produto']) && !empty($item['qtd_produto'])) {
                    $produto = Produto::find($item['id_produto']);
                    if ($produto) {
                        $total += $produto->preco * $item['qtd_produto'];
                    }
                }
            }

            $pedido->update([
                'cliente_id' => $request->id_cliente,
                'status' => $request->status ?? $pedido->status,
                'metodo_pagamento' => $request->metodo_pagamento ?? $pedido->metodo_pagamento,
                'total' => $total,
                'observacoes' => $request->observacoes ?? $pedido->observacoes,
            ]);

            foreach ($request->lista_produtos as $item) {
                if (!empty($item['id_produto']) && !empty($item['qtd_produto'])) {
                    $produto = Produto::find($item['id_produto']);
                    if ($produto) {
                        $quantidade = (int)$item['qtd_produto'];
                        PedidoProduto::create([
                            'pedido_id' => $pedido->id,
                            'produto_id' => $produto->id,
                            'quantidade' => $quantidade,
                        ]);

                        $produto->estoque = max(0, $produto->estoque - $quantidade);
                        $produto->vendidos += $quantidade;
                        $produto->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Pedido #{$pedido->numero_pedido} atualizado!"
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
            $pedido = Pedido::where('usuario_id', $usuario_id)->findOrFail($id);

            $produtos = PedidoProduto::where('pedido_id', $pedido->id)->get();
            foreach ($produtos as $p) {
                $produto = Produto::find($p->produto_id);
                if ($produto) {
                    $produto->estoque += $p->quantidade;
                    $produto->vendidos = max(0, $produto->vendidos - $p->quantidade);
                    $produto->save();
                }
            }

            PedidoProduto::where('pedido_id', $pedido->id)->delete();
            $pedido->delete();

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Pedido removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
