<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $filters = $request->only(['search', 'sort_by', 'sort_direction']);
        
        $produtos = Produto::filter($filters)->paginate($perPage);
        
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        $scenario = "create";
        return view('produtos.form',compact( 'scenario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:255',
        ]);

        Produto::create($request->all());

        return redirect()->route('produtos.index')
                        ->with('success', 'Produto criado com sucesso!');
    }

    public function show(Produto $produto)
    {
        $scenario = "view";
        return view('produtos.form', compact('produto', 'scenario'));
    }

    public function edit(Produto $produto)
    {
        $scenario = "edit";
        return view('produtos.form', compact('produto','scenario'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:255',
        ]);

        $produto->update($request->all());

        return redirect()->route('produtos.index')
                        ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        try {
            $produto->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto deletado com sucesso!'
                ]);
            }
            
            return redirect()->route('produtos.index')
                           ->with('success', 'Produto deletado com sucesso!');
                           
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao deletar produto!'
                ], 500);
            }
            
            return redirect()->route('produtos.index')
                           ->with('error', 'Erro ao deletar produto!');
        }
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Produto::whereIn('id', $ids)->delete();
        
        return response()->json(['success' => true]);
    }
}
