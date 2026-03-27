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

    public function get_vendas_semanais()
    {

        $hoje = Carbon::today();

        $total_pedidos = Pedido::whereDate('created_at', $hoje)->count();
        $pendentes = Pedido::where('status', 'pendente')->count();
        $entregues = Pedido::where('status', 'entregue')->count();
        $receita = Pedido::where('status', 'entregue')->sum('total');

        $pedidos = Pedido::orderBy('created_at', 'desc')->take(5)->get();
        $produtos = Produto::orderBy('vendidos', 'desc')->take(4)->get();


        $meta_vendas_mensal = 11200;
        $porcentagem_meta = ($meta_vendas_mensal > 0) ? round(($receita / $meta_vendas_mensal) * 100) : 0;
        $porcentagem_barra_vendas = $porcentagem_meta > 100 ? 100 : $porcentagem_meta;

        $taxa_entrega = ($total_pedidos > 0) ? round(($entregues / $total_pedidos) * 100) : 0;

        $performance = [
            'vendas' => [
                'receita_atual' => $receita,
                'meta_valor' => $meta_vendas_mensal,
                'porcentagem' => $porcentagem_meta,
                'porcentagem_barra' => $porcentagem_barra_vendas
            ],
            'entregas' => [
                'taxa_sucesso' => $taxa_entrega
            ]
        ];

        $vendas = Pedido::select(
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
            'pedidos' => $pedidos,
            'produtos' => $produtos,
            'performance' => $performance,
            'labels' => $labels,
            'series' => $valores
        ]);
    }
}
