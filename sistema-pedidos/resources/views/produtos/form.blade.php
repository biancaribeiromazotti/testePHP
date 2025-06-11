@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-box text-primary"></i> 
                @if($scenario == 'edit')
                    Editar Produto
                @elseif($scenario == 'view')
                    Visualizar Produto
                @else
                    Novo Produto
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">Produtos</a></li>
                    <li class="breadcrumb-item active">
                        @if($scenario == 'edit')
                            Editar Produto
                        @elseif($scenario == 'view')
                            Visualizar Produto
                        @else
                            Novo Produto
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-center">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Dados do Produto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $scenario == 'edit' ? route('produtos.update', $produto->id) : route('produtos.store') }}" method="POST" id="produtoForm">
                        @csrf
                        @if($scenario == 'edit')
                            @method('PUT')
                        @endif
                        
                        <!-- Dados Básicos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle"></i> Dados do Produto
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="codigo" class="form-label">Código do Produto <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('codigo') is-invalid @enderror" 
                                       id="codigo" 
                                       name="codigo" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $produto->codigo : old('codigo') }}"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       required>
                                @error('codigo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" 
                                       name="nome" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $produto->nome : old('nome') }}"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoria</label>
                                <input type="text" 
                                       class="form-control @error('categoria') is-invalid @enderror" 
                                       id="categoria" 
                                       name="categoria" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $produto->categoria : old('categoria') }}" 
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       placeholder="Ex: Eletrônicos, Roupas, Casa">
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="preco" class="form-label">Preço <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" 
                                           class="form-control @error('preco') is-invalid @enderror" 
                                           id="preco" 
                                           name="preco" 
                                           value="{{ ($scenario == 'view' || $scenario == 'edit') ? $produto->preco : old('preco') }}" 
                                           {{ $scenario == 'view' ? 'disabled' : '' }}
                                           step="0.01"
                                           min="0"
                                           placeholder="0,00"
                                           required>
                                    @error('preco')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="estoque" class="form-label">Quantidade em Estoque <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('estoque') is-invalid @enderror" 
                                       id="estoque" 
                                       name="estoque" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $produto->estoque : old('estoque') }}" 
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       min="0"
                                       placeholder="0"
                                       required>
                                @error('estoque')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="4" 
                                          {{ $scenario == 'view' ? 'disabled' : '' }}
                                          placeholder="Descreva o produto...">{{ ($scenario == 'view' || $scenario == 'edit') ? $produto->descricao : old('descricao') }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($scenario != 'view')
                            <!-- Botões -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> 
                                            @if($scenario == 'edit')
                                                Atualizar Produto
                                            @else
                                                Salvar Produto
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
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Formatação de preço
    $('#preco').on('input', function() {
        let value = this.value;
        // Remove caracteres não numéricos exceto ponto e vírgula
        value = value.replace(/[^\d.,]/g, '');
        // Substitui vírgula por ponto
        value = value.replace(',', '.');
        this.value = value;
    });

    // Validação do formulário
    $('#produtoForm').on('submit', function(e) {
        @if($scenario != 'view')
        let isValid = true;
        
        // Validar campos obrigatórios
        const requiredFields = ['codigo','nome', 'preco', 'estoque'];
        requiredFields.forEach(field => {
            const element = $(`#${field}`);
            if (!element.val().trim()) {
                element.addClass('is-invalid');
                isValid = false;
            } else {
                element.removeClass('is-invalid');
            }
        });

        // Validar preço
        const preco = parseFloat($('#preco').val());
        if (isNaN(preco) || preco < 0) {
            $('#preco').addClass('is-invalid');
            isValid = false;
        }

        // Validar estoque
        const estoque = parseInt($('#estoque').val());
        if (isNaN(estoque) || estoque < 0) {
            $('#estoque').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios corretamente.');
        }
        @endif
    });

    // Remover classe de erro quando o usuário começar a digitar
    $('.form-control').on('input', function() {
        $(this).removeClass('is-invalid');
    });
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

.input-group-text {
    background-color: #e9ecef;
    border-color: #ced4da;
    color: #495057;
    font-weight: 500;
}

.breadcrumb {
    background: none;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}
</style>
@endsection