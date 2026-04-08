<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;

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
            return response()->json([
                'status' => 'error',
                'message' => 'ID do usuário é obrigatório.'
            ], 400);
        }

        $hoje = now();
        $ontem = now()->subDay();


        $pedidos_hoje = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [$hoje->copy()->startOfDay(), $hoje->copy()->endOfDay()]);

        $total_pedidos = (clone $pedidos_hoje)->count();
        $pendentes = (clone $pedidos_hoje)->where('status', 'pendente')->count();
        $entregues = (clone $pedidos_hoje)->where('status', 'entregue')->count();
        $cancelados = (clone $pedidos_hoje)->where('status', 'cancelado')->count();


        $receita = (clone $pedidos_hoje)->sum('total');

        $pedidos_ontem_query = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [$ontem->copy()->startOfDay(), $ontem->copy()->endOfDay()]);

        $pedidos_ontem = (clone $pedidos_ontem_query)->count();
        $pendentes_ontem = (clone $pedidos_ontem_query)->where('status', 'pendente')->count();
        $entregues_ontem = (clone $pedidos_ontem_query)->where('status', 'entregue')->count();
        $receita_ontem = (clone $pedidos_ontem_query)->sum('total');


        $pedidos = Pedido::where('usuario_id', $id_usuario)
            ->latest()
            ->take(5)
            ->get();


        $produtos = Produto::where('usuario_id', $id_usuario)
            ->orderBy('vendidos', 'desc')
            ->take(5)
            ->get();


        $receita_atual = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total');

        $receita_passado = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');

        $crescimento_receita = ($receita_passado > 0)
            ? round((($receita_atual - $receita_passado) / $receita_passado) * 100)
            : 100;

        $entregues_atual = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('status', 'entregue')
            ->count();

        $entregues_passado = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->where('status', 'entregue')
            ->count();

        $crescimento_entregues = ($entregues_passado > 0)
            ? round((($entregues_atual - $entregues_passado) / $entregues_passado) * 100)
            : 100;

        $total_atual = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $total_passado = Pedido::where('usuario_id', $id_usuario)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        $taxa_atual = ($total_atual > 0)
            ? round(($entregues_atual / $total_atual) * 100)
            : 0;

        $taxa_passado = ($total_passado > 0)
            ? round(($entregues_passado / $total_passado) * 100)
            : 0;

        $crescimento_taxa = ($taxa_passado > 0)
            ? round((($taxa_atual - $taxa_passado) / $taxa_passado) * 100)
            : 0;

        $performance = [
            'receita_atual' => (float) $receita_atual,
            'receita_passado' => (float) $receita_passado,
            'crescimento_receita' => $crescimento_receita,
            'entregues_atual' => $entregues_atual,
            'entregues_passado' => $entregues_passado,
            'crescimento_entregues' => $crescimento_entregues,
            'taxa_atual' => $taxa_atual,
            'taxa_passado' => $taxa_passado,
            'crescimento_taxa' => $crescimento_taxa
        ];


        $inicio_atual = now()->subDays(6)->startOfDay();
        $fim_atual = now()->endOfDay();

        $inicio_passada = now()->subDays(13)->startOfDay();
        $fim_passada = now()->subDays(7)->endOfDay();

        $vendas_atual = Pedido::where('usuario_id', $id_usuario)
            ->where('status', 'entregue')
            ->whereBetween('created_at', [$inicio_atual, $fim_atual])
            ->selectRaw('DATE(created_at) as data, SUM(total) as total')
            ->groupBy('data')
            ->pluck('total', 'data');

        $vendas_passada = Pedido::where('usuario_id', $id_usuario)
            ->where('status', 'entregue')
            ->whereBetween('created_at', [$inicio_passada, $fim_passada])
            ->selectRaw('DATE(created_at) as data, SUM(total) as total')
            ->groupBy('data')
            ->pluck('total', 'data');

        $labels = [];
        $serie_atual = [];
        $serie_passada = [];

        for ($i = 6; $i >= 0; $i--) {
            $data = now()->subDays($i)->format('Y-m-d');
            $data_passada = now()->subDays($i + 7)->format('Y-m-d');

            $labels[] = now()->subDays($i)->format('d/m');
            $serie_atual[] = (float) ($vendas_atual[$data] ?? 0);
            $serie_passada[] = (float) ($vendas_passada[$data_passada] ?? 0);
        }

        return response()->json([
            'status' => 'success',
            'total_pedidos' => $total_pedidos,
            'pendentes' => $pendentes,
            'entregues' => $entregues,
            'cancelados' => $cancelados,
            'receita' => (float) $receita,
            'pedidos_ontem' => $pedidos_ontem,
            'pendentes_ontem' => $pendentes_ontem,
            'entregues_ontem' => $entregues_ontem,
            'receita_ontem' => (float) $receita_ontem,
            'pedidos' => $pedidos,
            'produtos' => $produtos,
            'performance' => $performance,
            'labels' => $labels,
            'series_atual' => $serie_atual,
            'series_passada' => $serie_passada
        ]);
    }
}
