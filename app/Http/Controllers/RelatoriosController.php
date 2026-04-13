<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RelatoriosController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('relatorios');
    }

    public function getDados(Request $request)
    {
        $usuariosID = $request->id_cliente;

        $totalPedidos = DB::table('pedidos')
            ->where('usuario_id', $usuariosID)
            ->count();

        $ticketMedio = DB::table('pedidos')
            ->where('usuario_id', $usuariosID)
            ->where('status', 'entregue')
            ->avg('total') ?? 0;

        $valorEmEstoque = DB::table('produtos')
            ->where('usuario_id', $usuariosID)
            ->where('status', 'ativo')
            ->sum(DB::raw('estoque * custo'));

        $lucroEstimado = DB::table('pedido_produto')
            ->join('pedidos', 'pedido_produto.pedido_id', '=', 'pedidos.id')
            ->join('produtos', 'pedido_produto.produto_id', '=', 'produtos.id')
            ->where('pedidos.usuario_id', $usuariosID)
            ->where('pedidos.status', 'entregue')
            ->sum(DB::raw('(pedido_produto.preco_unitario - produtos.custo) * pedido_produto.quantidade'));

        $faturamentoMensal = DB::table('pedidos')
            ->where('usuario_id', $usuariosID)
            ->where('status', 'entregue')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%m/%Y') as mes"),
                DB::raw('SUM(total) as faturamento')
            )
            ->groupBy('mes')
            ->orderByRaw('MIN(created_at) asc')
            ->limit(6)
            ->get();

        $receitaVsCusto = DB::table('pedido_produto')
            ->join('pedidos', 'pedido_produto.pedido_id', '=', 'pedidos.id')
            ->join('produtos', 'pedido_produto.produto_id', '=', 'produtos.id')
            ->where('pedidos.usuario_id', $usuariosID)
            ->where('pedidos.status', 'entregue')
            ->select(
                DB::raw("DATE_FORMAT(pedidos.created_at, '%m/%Y') as mes"),
                DB::raw('SUM(pedido_produto.quantidade * pedido_produto.preco_unitario) as receita'),
                DB::raw('SUM(pedido_produto.quantidade * produtos.custo) as custo')
            )
            ->groupBy('mes')
            ->orderByRaw('MIN(pedidos.created_at) asc')
            ->limit(6)
            ->get();

        $estoqueSaudavel = DB::table('produtos')
            ->where('usuario_id', $usuariosID)
            ->where('status', 'ativo')
            ->whereColumn('estoque', '>', 'estoque_minimo')
            ->count();

        $estoqueAlerta = DB::table('produtos')
            ->where('usuario_id', $usuariosID)
            ->where('status', 'ativo')
            ->whereColumn('estoque', '<=', 'estoque_minimo')
            ->count();

        $saudeEstoque = [
            'Saudável' => $estoqueSaudavel,
            'Abaixo do Mínimo/Zerad' => $estoqueAlerta
        ];

        $faturamentoPorCategoria = DB::table('pedido_produto')
            ->join('pedidos', 'pedido_produto.pedido_id', '=', 'pedidos.id')
            ->join('produtos', 'pedido_produto.produto_id', '=', 'produtos.id')
            ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
            ->where('pedidos.usuario_id', $usuariosID)
            ->where('pedidos.status', 'entregue')
            ->select('categorias.nome', DB::raw('SUM(pedido_produto.quantidade * pedido_produto.preco_unitario) as total_vendido'))
            ->groupBy('categorias.id', 'categorias.nome')
            ->orderByDesc('total_vendido')
            ->get();

        $funilStatus = DB::table('pedidos')
            ->where('usuario_id', $usuariosID)
            ->select('status', DB::raw('count(*) as quantidade'))
            ->groupBy('status')
            ->get();

        $topProdutos = DB::table('pedido_produto')
            ->join('pedidos', 'pedido_produto.pedido_id', '=', 'pedidos.id')
            ->join('produtos', 'pedido_produto.produto_id', '=', 'produtos.id')
            ->where('pedidos.usuario_id', $usuariosID)
            ->where('pedidos.status', 'entregue')
            ->select('produtos.nome', DB::raw('SUM(pedido_produto.quantidade) as quantidade_vendida'))
            ->groupBy('produtos.id', 'produtos.nome')
            ->orderByDesc('quantidade_vendida')
            ->get();

        return response()->json([
            'totalPedidos' => $totalPedidos,
            'ticketMedio' => $ticketMedio,
            'valorEmEstoque' => $valorEmEstoque,
            'lucroEstimado' => $lucroEstimado,
            'faturamentoMensal' => $faturamentoMensal,
            'receitaVsCusto' => $receitaVsCusto,
            'saudeEstoque' => $saudeEstoque,
            'faturamentoPorCategoria' => $faturamentoPorCategoria,
            'funilStatus' => $funilStatus,
            'topProdutos' => $topProdutos
        ]);
    }
}