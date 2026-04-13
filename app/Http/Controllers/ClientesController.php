<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    public function index()
    {
        if (!Session::has('id')) {
            return redirect('/login');
        }

        return view('clientes');
    }

    public function get_clientes(Request $request)
    {
        $usuario_id = $request->id_usuario ?? Session::get('id');

        if (!$usuario_id) {
            return response()->json(['status' => 'erro', 'mensagem' => 'Não autorizado'], 401);
        }

        $query = Cliente::where('usuario_id', $usuario_id);

        if (!empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('empresa', 'like', "%{$busca}%");
            });
        }

        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        $clientes = $query->orderBy('nome', 'asc')->get();

        return response()->json([
            'status' => 'sucesso',
            'clientes' => $clientes
        ]);
    }

    public function salvar(Request $request)
    {
        try {
            DB::beginTransaction();

            if (empty($request->nome)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'O nome do cliente é obrigatório.'], 400);
            }

            $usuario_id = $request->id_usuario ?? Session::get('id');

            $cliente = Cliente::create([
                'usuario_id'  => $usuario_id,
                'nome'        => $request->nome,
                'email'       => $request->email ?? null,
                'telefone'    => $request->telefone ?? null,
                'empresa'     => $request->empresa ?? null,
                'endereco'    => $request->endereco ?? null,
                'observacoes' => $request->observacoes ?? null,
                'status'      => $request->status ?? 'ativo',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Cliente {$cliente->nome} cadastrado com sucesso!",
                'cliente' => $cliente
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function detalhes(Request $request, $id)
    {
        try {
            $usuario_id = $request->id_usuario ?? Session::get('id');
            $cliente = Cliente::where('usuario_id', $usuario_id)->findOrFail($id);

            return response()->json([
                'status' => 'sucesso',
                'cliente' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'erro', 'mensagem' => 'Cliente não encontrado.'], 404);
        }
    }

    public function atualizar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            if (empty($request->nome)) {
                return response()->json(['status' => 'erro', 'mensagem' => 'O nome não pode ficar em branco.'], 400);
            }

            $usuario_id = $request->id_usuario ?? Session::get('id');
            $cliente = Cliente::where('usuario_id', $usuario_id)->findOrFail($id);

            $cliente->update([
                'nome'        => $request->nome,
                'email'       => $request->email ?? $cliente->email,
                'telefone'    => $request->telefone ?? $cliente->telefone,
                'empresa'     => $request->empresa ?? $cliente->empresa,
                'endereco'    => $request->endereco ?? $cliente->endereco,
                'observacoes' => $request->observacoes ?? $cliente->observacoes,
                'status'      => $request->status ?? $cliente->status,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => "Dados de {$cliente->nome} atualizados!"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
        }
    }

    public function excluir(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $usuario_id = $request->id_usuario ?? Session::get('id');
            $cliente = Cliente::where('usuario_id', $usuario_id)->findOrFail($id);

            $cliente->delete();

            DB::commit();

            return response()->json([
                'status' => 'sucesso',
                'mensagem' => 'Cliente removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'erro', 'mensagem' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
