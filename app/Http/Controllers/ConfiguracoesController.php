<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class ConfiguracoesController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('configuracoes');
    }
}
