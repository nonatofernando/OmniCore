<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function form(Request $request)
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $senha = $request->input('senha');

        if (!$email || !$senha) {
            return redirect('/login?erro=campos');
        }

        $user = DB::table('usuarios')
            ->where('email', $email)
            ->first();

        if (!$user) {
            return redirect('/login?erro=usuario');
        }

        if (!Hash::check($senha, $user->senha)) {
            return redirect('/login?erro=senha');
        }

        Session::put('id', $user->id);
        Session::put('nome', $user->nome);

        return redirect('/');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
