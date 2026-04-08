<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;
use App\Models\Empresa; // Não esqueça de importar o Model da Empresa

class ConfiguracoesController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        // Carregamos o usuário junto com os dados da empresa (Eager Loading)
        $usuario = Usuario::with('empresa')->find(Session::get('id'));

        if (!$usuario) {
            return redirect('/login');
        }

        return view('configuracoes', compact('usuario'));
    }

    public function salvar(Request $request)
    {
        $usuario = Usuario::find(Session::get('id'));
        if (!$usuario) return redirect('/login');

        // Se o formulário da EMPRESA foi enviado
        if ($request->has('empresa')) {
            // Busca a empresa existente ou cria uma nova vinculada ao usuário
            $empresa = $usuario->empresa ?: new \App\Models\Empresa();

            // Mapeamento de todos os novos campos
            $empresa->nome     = $request->input('empresa');
            $empresa->cnpj     = $request->input('cnpj');
            $empresa->endereco = $request->input('endereco'); 
            $empresa->cidade   = $request->input('cidade');   
            $empresa->estado   = $request->input('estado');   

            $empresa->save();

            // Garante que o ID da empresa está vinculado ao usuário
            $usuario->empresa_id = $empresa->id;
            $usuario->save();
        }

        // Se o formulário do PERFIL foi enviado
        if ($request->has('nome')) {
            $usuario->nome = $request->input('nome');
            $usuario->email = $request->input('email');

            if ($request->filled('password')) {
                $usuario->password = \Illuminate\Support\Facades\Hash::make($request->password);
            }
            $usuario->save();
        }

        return redirect()->route('configuracoes')->with('sucesso', 'Alterações salvas com sucesso!');
    }
}
