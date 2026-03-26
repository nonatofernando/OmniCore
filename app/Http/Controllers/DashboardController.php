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

        $hoje = Carbon::today();

        $total_pedidos = Pedido::whereDate('created_at', $hoje)->count();
        $pendentes = Pedido::where('status', 'pendente')->count();
        $entregues = Pedido::where('status', 'entregue')->count();
        $receita = Pedido::where('status', 'entregue')->sum('total');

        $pedidos = Pedido::orderBy('created_at', 'desc')->take(5)->get();
        $produtos = Produto::orderBy('vendidos', 'desc')->take(4)->get();

        // 6. Métricas de Performance
        $performance_metrics = [
            ['label' => 'Meta Mensal', 'displayValue' => 'R$ ' . number_format($receita, 0, ',', '.'), 'percentage' => 75]
        ];

        return view('dashboard', compact(
            'total_pedidos',
            'pendentes',
            'entregues',
            'receita',
            'pedidos',
            'produtos',
            'performance_metrics'
        ));
    }

    public function get_vendas_semanais()
    {
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
            'labels' => $labels,
            'series' => $valores
        ]);
    }
}
