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

}
