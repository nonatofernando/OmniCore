<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientesController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('clientes');
    }

    public function getClientes(Request $request)
    {
        $clientes = Cliente::select('id', 'nome')
            ->where('usuario_id', $request->id_usuario)
            ->get();

        return response()->json(['clientes' => $clientes]);
    }
}
