<?php

namespace App\Http\Controllers;

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
}
