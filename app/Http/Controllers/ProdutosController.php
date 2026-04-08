<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('produtos');
    }
    public function getProdutos(Request $request)
    {

        if (!$request->id_usuario) {
            return response()->json(['status' => 'Nao encontrado'], 401);
        }

        $produtos = Produto::select('id', 'nome', 'preco')
            ->where('usuario_id', $request->id_usuario)
            ->get();

        return response()->json(['produtos' => $produtos]);
    }
}
