@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-shopping-cart text-primary"></i> 
                @if($scenario == 'edit')
                    Editar Pedido
                @elseif($scenario == 'view')
                    Visualizar Pedido
                @else
                    Novo Pedido
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
                    <li class="breadcrumb-item active">
                        @if($scenario == 'edit')
                            Editar Pedido
                        @elseif($scenario == 'view')
                            Visualizar Pedido
                        @else
                            Novo Pedido
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-center">
        <div class="col-lg-10 mb-4">
            <form action="{{ $scenario == 'edit' ? route('pedidos.update', $pedido->id) : route('pedidos.store') }}" method="POST" id="pedidoForm">
                @csrf
                @if($scenario == 'edit')
                    @method('PUT')
                @endif
                
                <!-- Dados do Pedido -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Dados do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($scenario == 'edit' || $scenario == 'view')
                                <div class="col-md-3 mb-3">
                                    <label for="codigo" class="form-label">Código do Pedido</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="codigo" 
                                           value="{{ $pedido->codigo }}"
                                           disabled>
                                </div>
                            @endif
                            
                            <div class="col-md-{{ ($scenario == 'edit' || $scenario == 'view') ? '6' : '8' }} mb-3">
                                <label for="cliente_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select class="form-select @error('cliente_id') is-invalid @enderror" 
                                        id="cliente_id" 
                                        name="cliente_id" 
                                        {{ $scenario == 'view' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Selecione um cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                                {{ (($scenario == 'view' || $scenario == 'edit') && isset($pedido) && $pedido->cliente_id == $cliente->id) ? 'selected' : (old('cliente_id') == $cliente->id ? 'selected' : '') }}>
                                            {{ $cliente->nome }} - {{ $cliente->email ?? $cliente->telefone ?? 'ID: ' . $cliente->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        {{ $scenario == 'view' ? 'disabled' : '' }}
                                        required>
                                    <option value="Em Aberto" {{ (($scenario == 'view' || $scenario == 'edit') && isset($pedido) && $pedido->status == 'Em Aberto') ? 'selected' : (old('status') == 'Em Aberto' ? 'selected' : 'selected') }}>Em Aberto</option>
                                    <option value="Pago" {{ (($scenario == 'view' || $scenario == 'edit') && isset($pedido) && $pedido->status == 'Pago') ? 'selected' : (old('status') == 'Pago' ? 'selected' : '') }}>Pago</option>
                                    <option value="Cancelado" {{ (($scenario == 'view' || $scenario == 'edit') && isset($pedido) && $pedido->status == 'Cancelado') ? 'selected' : (old('status') == 'Cancelado' ? 'selected' : '') }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="data_pedido" class="form-label">Data do Pedido <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control @error('data_pedido') is-invalid @enderror" 
                                       id="data_pedido" 
                                       name="data_pedido" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') && isset($pedido) ? $pedido->data_pedido->format('Y-m-d\TH:i') : old('data_pedido', now()->format('Y-m-d\TH:i')) }}"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       required>
                                @error('data_pedido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-8 mb-3">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                          id="observacoes" 
                                          name="observacoes" 
                                          rows="3" 
                                          {{ $scenario == 'view' ? 'disabled' : '' }}
                                          placeholder="Observações adicionais sobre o pedido...">{{ ($scenario == 'view' || $scenario == 'edit') && isset($pedido) ? $pedido->observacoes : old('observacoes') }}</textarea>
                                @error('observacoes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produtos do Pedido -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Produtos do Pedido</h5>
                        @if($scenario != 'view')
                            <button type="button" class="btn btn-light btn-sm" id="addProdutoBtn">
                                <i class="fas fa-plus"></i> Adicionar Produto
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="produtosContainer">
                            @if(($scenario == 'edit' || $scenario == 'view') && isset($pedido) && $pedido->produtos->count() > 0)
                                @foreach($pedido->produtos as $index => $produto)
                                    <div class="produto-item border rounded p-3 mb-3" data-index="{{ $index }}">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Produto <span class="text-danger">*</span></label>
                                                <select class="form-select produto-select" name="produtos[{{ $index }}][produto_id]" {{ $scenario == 'view' ? 'disabled' : '' }} required>
                                                    <option value="">Selecione um produto...</option>
                                                    @foreach($produtos as $prod)
                                                        <option value="{{ $prod->id }}" 
                                                                data-preco="{{ $prod->preco }}" 
                                                                data-estoque="{{ $prod->estoque }}"
                                                                {{ $produto->id == $prod->id ? 'selected' : '' }}>
                                                            {{ $prod->nome }} - R$ {{ number_format($prod->preco, 2, ',', '.') }} (Estoque: {{ $prod->estoque }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Qtd. <span class="text-danger">*</span></label>
                                                <input type="number" 
                                                       class="form-control quantidade-input" 
                                                       name="produtos[{{ $index }}][quantidade]" 
                                                       value="{{ $produto->pivot->quantidade }}"
                                                       min="1" 
                                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                                       required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Preço Unit.</label>
                                                <input type="number" 
                                                       class="form-control preco-input" 
                                                       name="produtos[{{ $index }}][preco_unitario]" 
                                                       value="{{ $produto->pivot->preco_unitario }}"
                                                       step="0.01" 
                                                       min="0" 
                                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                                       required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Subtotal</label>
                                                <input type="number" 
                                                       class="form-control subtotal-input bg-light" 
                                                       name="produtos[{{ $index }}][subtotal]" 
                                                       value="{{ $produto->pivot->subtotal }}"
                                                       step="0.01" 
                                                       readonly>
                                            </div>
                                            @if($scenario != 'view')
                                                <div class="col-md-2 text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-produto">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Produto inicial para criar novo pedido -->
                                @if($scenario != 'view')
                                    <div class="produto-item border rounded p-3 mb-3" data-index="0">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Produto <span class="text-danger">*</span></label>
                                                <select class="form-select produto-select" name="produtos[0][produto_id]" required>
                                                    <option value="">Selecione um produto...</option>
                                                    @foreach($produtos as $produto)
                                                        <option value="{{ $produto->id }}" 
                                                                data-preco="{{ $produto->preco }}" 
                                                                data-estoque="{{ $produto->estoque }}">
                                                            {{ $produto->nome }} - R$ {{ number_format($produto->preco, 2, ',', '.') }} (Estoque: {{ $produto->estoque }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Qtd. <span class="text-danger">*</span></label>
                                                <input type="number" 
                                                       class="form-control quantidade-input" 
                                                       name="produtos[0][quantidade]" 
                                                       value="1"
                                                       min="1" 
                                                       required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Preço Unit.</label>
                                                <input type="number" 
                                                       class="form-control preco-input" 
                                                       name="produtos[0][preco_unitario]" 
                                                       value="0.00"
                                                       step="0.01" 
                                                       min="0" 
                                                       required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Subtotal</label>
                                                <input type="number" 
                                                       class="form-control subtotal-input bg-light" 
                                                       name="produtos[0][subtotal]" 
                                                       value="0.00"
                                                       step="0.01" 
                                                       readonly>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-produto">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        @if($scenario != 'view' && (!isset($pedido) || $pedido->produtos->count() == 0))
                            <div class="text-center text-muted" id="noProdutosMessage" style="display: none;">
                                <p><i class="fas fa-box-open fa-2x mb-2"></i></p>
                                <p>Nenhum produto adicionado. Clique em "Adicionar Produto" para começar.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Totais -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator"></i> Totais</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3">
                                <label for="desconto" class="form-label">Desconto (%)</label>
                                <input type="number" 
                                       class="form-control @error('desconto') is-invalid @enderror" 
                                       id="desconto" 
                                       name="desconto" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') && isset($pedido) ? $pedido->desconto : old('desconto', 0) }}"
                                       step="0.01" 
                                       min="0" 
                                       max="100"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       placeholder="0.00">
                                @error('desconto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Subtotal</label>
                                <div class="form-control bg-light fs-5 fw-bold text-center" id="subtotalDisplay">R$ 0,00</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Desconto (R$)</label>
                                <div class="form-control bg-warning text-dark fs-5 fw-bold text-center" id="descontoDisplay">R$ 0,00</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Total Final</label>
                                <div class="form-control bg-success text-white fs-4 fw-bold text-center" id="totalDisplay">R$ 0,00</div>
                                <input type="hidden" name="total" id="totalInput" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                @if($scenario != 'view')
                    <!-- Botões -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('pedidos.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> 
                                    @if($scenario == 'edit')
                                        Atualizar Pedido
                                    @else
                                        Salvar Pedido
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let produtoIndex = {{ ($scenario == 'edit' && isset($pedido)) ? $pedido->produtos->count() : 1 }};

    // Adicionar novo produto
    $('#addProdutoBtn').click(function() {
        const produtoHtml = `
            <div class="produto-item border rounded p-3 mb-3" data-index="${produtoIndex}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Produto <span class="text-danger">*</span></label>
                        <select class="form-select produto-select" name="produtos[${produtoIndex}][produto_id]" required>
                            <option value="">Selecione um produto...</option>
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}" 
                                        data-preco="{{ $produto->preco }}" 
                                        data-estoque="{{ $produto->estoque }}">
                                    {{ $produto->nome }} - R$ {{ number_format($produto->preco, 2, ',', '.') }} (Estoque: {{ $produto->estoque }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Qtd. <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control quantidade-input" 
                               name="produtos[${produtoIndex}][quantidade]" 
                               value="1"
                               min="1" 
                               required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Preço Unit.</label>
                        <input type="number" 
                               class="form-control preco-input" 
                               name="produtos[${produtoIndex}][preco_unitario]" 
                               value="0.00"
                               step="0.01" 
                               min="0" 
                               required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subtotal</label>
                        <input type="number" 
                               class="form-control subtotal-input bg-light" 
                               name="produtos[${produtoIndex}][subtotal]" 
                               value="0.00"
                               step="0.01" 
                               readonly>
                    </div>
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-produto">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#produtosContainer').append(produtoHtml);
        $('#noProdutosMessage').hide();
        produtoIndex++;
        calcularTotais();
    });

    // Remover produto
    $(document).on('click', '.remove-produto', function() {
        if ($('.produto-item').length > 1) {
            $(this).closest('.produto-item').remove();
            calcularTotais();
        } else {
            $(this).closest('.produto-item').remove();
            $('#noProdutosMessage').show();
            calcularTotais();
        }
    });

    // Quando selecionar um produto, preencher o preço
    $(document).on('change', '.produto-select', function() {
        const preco = $(this).find('option:selected').data('preco') || 0;
        const estoque = $(this).find('option:selected').data('estoque') || 0;
        const produtoItem = $(this).closest('.produto-item');
        
        produtoItem.find('.preco-input').val(parseFloat(preco).toFixed(2));
        
        // Verificar se a quantidade não excede o estoque
        const quantidadeInput = produtoItem.find('.quantidade-input');
        quantidadeInput.attr('max', estoque);
        
        if (parseInt(quantidadeInput.val()) > estoque) {
            quantidadeInput.val(estoque);
        }
        
        calcularSubtotal(produtoItem);
    });

    // Recalcular quando quantidade ou preço mudar
    $(document).on('input', '.quantidade-input, .preco-input', function() {
        const produtoItem = $(this).closest('.produto-item');
        
        // Verificar estoque se for o campo quantidade
        if ($(this).hasClass('quantidade-input')) {
            const estoque = produtoItem.find('.produto-select option:selected').data('estoque') || 999999;
            const quantidade = parseInt($(this).val()) || 0;
            
            if (quantidade > estoque) {
                alert(`Quantidade não pode ser maior que o estoque disponível (${estoque})`);
                $(this).val(estoque);
            }
        }
        
        calcularSubtotal(produtoItem);
    });

    // Recalcular quando desconto mudar
    $('#desconto').on('input', function() {
        calcularTotais();
    });

    // Função para calcular subtotal de um produto
    function calcularSubtotal(produtoItem) {
        const quantidade = parseFloat(produtoItem.find('.quantidade-input').val()) || 0;
        const preco = parseFloat(produtoItem.find('.preco-input').val()) || 0;
        const subtotal = quantidade * preco;
        
        produtoItem.find('.subtotal-input').val(subtotal.toFixed(2));
        calcularTotais();
    }

    // Função para calcular totais gerais
    function calcularTotais() {
        let subtotal = 0;
        
        $('.subtotal-input').each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });
        
        const descontoPercent = parseFloat($('#desconto').val()) || 0;
        const valorDesconto = subtotal * (descontoPercent / 100);
        const total = subtotal - valorDesconto;
        
        $('#subtotalDisplay').text('R$ ' + subtotal.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        
        $('#descontoDisplay').text('R$ ' + valorDesconto.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        
        $('#totalDisplay').text('R$ ' + total.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        
        $('#totalInput').val(total.toFixed(2));
    }

    // Validação do formulário
    $('#pedidoForm').on('submit', function(e) {
        @if($scenario != 'view')
        let isValid = true;
        
        // Validar cliente
        if (!$('#cliente_id').val()) {
            $('#cliente_id').addClass('is-invalid');
            isValid = false;
        }

        // Validar se há pelo menos um produto
        if ($('.produto-item').length === 0) {
            alert('É necessário adicionar pelo menos um produto ao pedido.');
            isValid = false;
        }

        // Validar produtos
        $('.produto-item').each(function() {
            const produtoSelect = $(this).find('.produto-select');
            const quantidadeInput = $(this).find('.quantidade-input');
            const precoInput = $(this).find('.preco-input');
            
            if (!produtoSelect.val()) {
                produtoSelect.addClass('is-invalid');
                isValid = false;
            }
            
            if (!quantidadeInput.val() || parseInt(quantidadeInput.val()) < 1) {
                quantidadeInput.addClass('is-invalid');
                isValid = false;
            }
            
            if (!precoInput.val() || parseFloat(precoInput.val()) < 0) {
                precoInput.addClass('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios corretamente.');
        }
        @endif
    });

    // Remover classe de erro quando o usuário interagir
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Calcular totais iniciais
    calcularTotais();
    
    // Verificar se deve mostrar mensagem de "nenhum produto"
    if ($('.produto-item').length === 0) {
        $('#noProdutosMessage').show();
    }
});
</script>
@endsection

@section('styles')
<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

.border-bottom {
    border-bottom: 2px solid #e9ecef !important;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.btn {
    border-radius: 5px;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.breadcrumb {
    background: none;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}

.produto-item {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef !important;
}

.produto-item:hover {
    background-color: #e9ecef;
    border-color: #adb5bd !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

#totalDisplay {
    font-size: 1.2rem;
}

#subtotalDisplay, #descontoDisplay {
    font-size: 1.1rem;
}

.remove-produto {
    width: 38px;
    height: 38px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.subtotal-input {
    font-weight: 600;
    text-align: right;
}

#noProdutosMessage {
    padding: 2rem;
}

@media (max-width: 768px) {
    .produto-item .row > div {
        margin-bottom: 1rem;
    }
    
    .remove-produto {
        width: 100%;
        margin-top: 10px;
    }
    
    .col-md-4, .col-md-3, .col-md-2 {
        margin-bottom: 1rem;
    }
}

/* Status badges */
.badge-pendente { background-color: #ffc107; }
.badge-processando { background-color: #17a2b8; }
.badge-enviado { background-color: #007bff; }
.badge-entregue { background-color: #28a745; }
.badge-cancelado { background-color: #dc3545; }
</style>
@endsection