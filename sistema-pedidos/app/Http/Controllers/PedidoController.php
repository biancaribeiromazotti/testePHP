<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $filters = $request->only(['search', 'sort_by', 'sort_direction']);
        
        $pedidos = Pedido::filter($filters)->paginate($perPage);
        
        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $scenario = "create";
        return view('pedidos.form',compact( 'scenario','clientes','produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'status' => 'required|string',
            'total' => 'required|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0|max:100',
            'observacoes' => 'nullable|string',
            'produtos' => 'required|array|min:1',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            
            // Criar o pedido principal
            $pedido = Pedido::create([
                'cliente_id' => (int) $request->cliente_id,
                'status' => (string) ($request->status ?? 'Em Aberto'),
                'total' => (float) $request->total,
                'desconto' => (float) ($request->desconto ?? 0),
                'observacoes' => (string) ($request->observacoes ?? ''),
                'data_pedido' => $request->data_pedido ?? now(),
            ]);

            // Criar os itens do pedido
            foreach ($request->produtos as $produtoData) {
                // Verificar estoque disponível
                $produto = Produto::find($produtoData['produto_id']);
                
                if ($produto->estoque < $produtoData['quantidade']) {
                    throw new \Exception("Estoque insuficiente para o produto: {$produto->nome}");
                }
                
                // Criar item do pedido
                $pedido->itemPedido()->create([
                    'produto_id' => $produtoData['produto_id'],
                    'quantidade' => $produtoData['quantidade'],
                    'preco_unitario' => $produtoData['preco_unitario'],
                    'subtotal' => $produtoData['quantidade'] * $produtoData['preco_unitario'],
                ]);
                
                // Atualizar estoque do produto
                $produto->decrement('estoque', $produtoData['quantidade']);
            }

            DB::commit();

            return redirect()->route('pedidos.index')
                            ->with('success', 'Pedido criado com sucesso!');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erro ao criar pedido: ' . $e->getMessage());
        }
    }

    public function show(Pedido $pedido)
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $scenario = "view";
        return view('pedidos.form', compact('pedido', 'scenario', 'clientes','produtos'));
    }

    public function edit(Pedido $pedido)
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $scenario = "edit";
        return view('pedidos.form', compact('pedido','scenario','clientes','produtos'));
    }

    // public function update(Request $request, Pedido $pedido)
    // {
    //     $request->validate([
    //         'cliente_id' => 'required|exists:clientes,id',
    //         'status' => 'required|string',
    //         'total' => 'required|numeric|min:0',
    //         'desconto' => 'nullable|numeric|min:0|max:100',
    //         'observacoes' => 'nullable|string',
    //         'produtos' => 'required|array|min:1',
    //         'produtos.*.produto_id' => 'required|exists:produtos,id',
    //         'produtos.*.quantidade' => 'required|integer|min:1',
    //         'produtos.*.preco_unitario' => 'required|numeric|min:0',
    //     ]);

    //     try {
    //         DB::beginTransaction();
            
            
    //         // Criar o pedido principal
    //         $pedido = Pedido::update([
    //             'cliente_id' => (int) $request->cliente_id,
    //             'status' => (string) ($request->status ?? 'Em Aberto'),
    //             'total' => (float) $request->total,
    //             'desconto' => (float) ($request->desconto ?? 0),
    //             'observacoes' => (string) ($request->observacoes ?? ''),
    //             'data_pedido' => $request->data_pedido ?? now(),
    //         ]);

    //         // Criar os itens do pedido
    //         foreach ($request->produtos as $produtoData) {
    //             // Verificar estoque disponível
    //             $produto = Produto::find($produtoData['produto_id']);
                
    //             if ($produto->estoque < $produtoData['quantidade']) {
    //                 throw new \Exception("Estoque insuficiente para o produto: {$produto->nome}");
    //             }
                
    //             // Criar item do pedido
    //             $pedido->itemPedido()->update([
    //                 'produto_id' => $produtoData['produto_id'],
    //                 'quantidade' => $produtoData['quantidade'],
    //                 'preco_unitario' => $produtoData['preco_unitario'],
    //                 'subtotal' => $produtoData['quantidade'] * $produtoData['preco_unitario'],
    //             ]);
                
    //             // Atualizar estoque do produto
    //             $produto->decrement('estoque', $produtoData['quantidade']);
    //         }

    //         DB::commit();

    //         return redirect()->route('pedidos.index')
    //                         ->with('success', 'Pedido atualizado com sucesso!');
                            
    //     } catch (\Exception $e) {
    //         DB::rollback();
            
    //         return redirect()->back()
    //                         ->withInput()
    //                         ->with('error', 'Erro ao atualizar pedido: ' . $e->getMessage());
    //     }
    // }

    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'status' => 'required|string',
            'total' => 'required|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0|max:100',
            'observacoes' => 'nullable|string',
            'produtos' => 'required|array|min:1',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            // Restaurar estoque dos itens antigos
            foreach ($pedido->itemPedido as $itemAntigo) {
                $produto = Produto::find($itemAntigo->produto_id);
                $produto->increment('estoque', $itemAntigo->quantidade);
            }
            
            // Remover itens antigos do pedido
            $pedido->itemPedido()->delete();
            
            // Atualizar o pedido principal
            $pedido->update([
                'cliente_id' => (int) $request->cliente_id,
                'status' => (string) ($request->status ?? 'Em Aberto'),
                'total' => (float) $request->total,
                'desconto' => (float) ($request->desconto ?? 0),
                'observacoes' => (string) ($request->observacoes ?? ''),
                'data_pedido' => $request->data_pedido ?? $pedido->data_pedido, // Mantém a data original se não informada
            ]);

            // Criar os novos itens do pedido
            foreach ($request->produtos as $produtoData) {
                // Verificar estoque disponível
                $produto = Produto::find($produtoData['produto_id']);
                
                if ($produto->estoque < $produtoData['quantidade']) {
                    throw new \Exception("Estoque insuficiente para o produto: {$produto->nome}");
                }
                
                // Criar novo item do pedido
                $pedido->itemPedido()->create([
                    'produto_id' => $produtoData['produto_id'],
                    'quantidade' => $produtoData['quantidade'],
                    'preco_unitario' => $produtoData['preco_unitario'],
                    'subtotal' => $produtoData['quantidade'] * $produtoData['preco_unitario'],
                ]);
                
                // Decrementar estoque do produto
                $produto->decrement('estoque', $produtoData['quantidade']);
            }

            DB::commit();

            return redirect()->route('pedidos.index')
                            ->with('success', 'Pedido atualizado com sucesso!');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erro ao atualizar pedido: ' . $e->getMessage());
        }
    }

    public function destroy(Pedido $pedido)
    {
        try {
            $pedido->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pedido deletado com sucesso!'
                ]);
            }
            
            return redirect()->route('pedidos.index')
                           ->with('success', 'Pedido deletado com sucesso!');
                           
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao deletar pedido!'
                ], 500);
            }
            
            return redirect()->route('pedidos.index')
                           ->with('error', 'Erro ao deletar pedido!');
        }
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Pedido::whereIn('id', $ids)->delete();
        
        return response()->json(['success' => true]);
    }
}
