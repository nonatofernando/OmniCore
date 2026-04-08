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

        $query = Pedido::with('cliente')->where('usuario_id', $request->id_usuario);

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
            'produtos' => Produto::all(['id', 'nome', 'preco'])
        ]);
    }

    public function salvar(Request $request)
    {
        try {
            if (!$request->id_cliente) {
                return response()->json([
                    'status' => 'erro',
                    'mensagem' => 'Cliente é obrigatório'
                ], 400);
            }

            if (!$request->lista_produtos || count($request->lista_produtos) === 0) {
                return response()->json([
                    'status' => 'erro',
                    'mensagem' => 'Adicione produtos ao pedido'
                ], 400);
            }

            $ultimoPedido = Pedido::orderBy('id', 'desc')->first();
            $numero_pedido = $ultimoPedido ? $ultimoPedido->numero_pedido + 1 : 1;

            $pedido = new Pedido();
            $pedido->numero_pedido = $numero_pedido;
            $pedido->cliente_id = $request->id_cliente;
            $pedido->usuario_id = $request->id_usuario;
            $pedido->total = $request->valor_total;
            $pedido->status = 'pendente';
            $pedido->metodo_pagamento = $request->metodo_pagamento;
            $pedido->observacoes = $request->obs_pedido;
            $pedido->save();

            foreach ($request->lista_produtos as $item) {
                PedidoProduto::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['id_produto'],
                    'quantidade' => $item['qtd_produto'],
                ]);
            }

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Pedido #' . str_pad($numero_pedido, 6, '0', STR_PAD_LEFT) . ' criado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => $e->getMessage()
            ], 500);
        }
    }

    public function detalhes($id)
    {
        $pedido = Pedido::with(['produtos', 'cliente'])->findOrFail($id);
        return view('pedidos.detalhes', compact('pedido'));
    }
}
