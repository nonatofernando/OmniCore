<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('dashboard');
    }

    public function get_vendas_semanais(Request $request)
    {

        $id_usuario = $request->id_usuario;
        if (!$id_usuario) {
            return response()->json(['status' => 'error', 'message' => 'ID do usuário é obrigatório.'], 400);
        }
        $hoje = Carbon::today();
        $ontem = Carbon::yesterday();

        $total_pedidos = Pedido::where('usuario_id', $id_usuario)->whereDate('created_at', $hoje)->count();

        $pendentes = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $hoje)
            ->where('status', 'pendente')
            ->count();

        $entregues = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $hoje)
            ->where('status', 'entregue')
            ->count();

        $receita = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $hoje)
            ->where('status', 'entregue')
            ->sum('total');

        $pedidos_ontem = Pedido::where('usuario_id', $id_usuario)->whereDate('created_at', $ontem)->count();

        $pendentes_ontem = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $ontem)
            ->where('status', 'pendente')
            ->count();

        $entregues_ontem = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $ontem)
            ->where('status', 'entregue')
            ->count();

        $receita_ontem = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $ontem)
            ->where('status', 'entregue')
            ->sum('total');

        $pedidos = Pedido::where('usuario_id', $id_usuario)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $produtos = Produto::where('usuario_id', $id_usuario)
            ->orderBy('vendidos', 'desc')
            ->take(4)
            ->get();

        $cancelados = Pedido::where('usuario_id', $id_usuario)
            ->whereDate('created_at', $hoje)
            ->where('status', 'cancelado')
            ->count();

        $crescimento_receita = ($receita_ontem > 0)
            ? round((($receita - $receita_ontem) / $receita_ontem) * 100)
            : 100;

        $meta_vendas_mensal = 11200;

        $porcentagem_meta = ($meta_vendas_mensal > 0)
            ? round(($receita / $meta_vendas_mensal) * 100)
            : 0;

        $porcentagem_barra_vendas = $porcentagem_meta > 100 ? 100 : $porcentagem_meta;

        $taxa_entrega = ($total_pedidos > 0)
            ? round(($entregues / $total_pedidos) * 100)
            : 0;

        $performance = [
            'vendas' => [
                'receita_atual' => $receita,
                'meta_valor' => $meta_vendas_mensal,
                'porcentagem' => $porcentagem_meta,
                'porcentagem_barra' => $porcentagem_barra_vendas,
                'crescimento' => $crescimento_receita
            ]
        ];

        $vendas = Pedido::where('usuario_id', $id_usuario)
            ->select(
                DB::raw('DATE(created_at) as data'),
                DB::raw('SUM(total) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('data')
            ->orderBy('data', 'asc')
            ->get();

        $labels = [];
        $valores = [];

        foreach ($vendas as $venda) {
            $labels[] = date('d/m', strtotime($venda->data));
            $valores[] = (float) $venda->total;
        }

        return response()->json([
            'status' => 'success',
            'total_pedidos' => $total_pedidos,
            'pendentes' => $pendentes,
            'entregues' => $entregues,
            'receita' => $receita,
            'pedidos_ontem' => $pedidos_ontem,
            'pendentes_ontem' => $pendentes_ontem,
            'entregues_ontem' => $entregues_ontem,
            'receita_ontem' => $receita_ontem,
            'pedidos' => $pedidos,
            'produtos' => $produtos,
            'performance' => $performance,
            'labels' => $labels,
            'series' => $valores
        ]);
    }
}
