<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $filters = $request->only(['search', 'sort_by', 'sort_direction']);
        
        $clientes = Cliente::filter($filters)->paginate($perPage);
        
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10'
        ]);

        Cliente::form($request->all());

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente criado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        $scenario = "view";
        return view('clientes.form', compact('cliente', 'scenario'));
    }

    public function edit(Cliente $cliente)
    {
        $scenario = "edit";
        return view('clientes.form', compact('cliente','scenario'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id,
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10'
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                        ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        print_r($cliente);exit();
        // $cliente->delete();

        // return redirect()->route('clientes.index')
        //                 ->with('success', 'Cliente deletado com sucesso!');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Cliente::whereIn('id', $ids)->delete();
        
        return response()->json(['success' => true]);
    }
}