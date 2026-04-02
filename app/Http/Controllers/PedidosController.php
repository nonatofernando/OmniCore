<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $id_usuario = $request->id_usuario;

        $pedidos = Pedido::where('usuario_id', $id_usuario)
            ->with('produtos') 
            ->get();

        return response()->json([
            'resposta' => 'sucesso', 
            'pedidos' => $pedidos
        ]);
    }
}
