@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-user-plus text-primary"></i> 
                @if($scenario == 'edit')
                    Editar Cliente
                @elseif($scenario == 'view')
                    Visualizar Cliente
                @else
                    Novo Cliente
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li class="breadcrumb-item active">
                        @if($scenario == 'edit')
                            Editar Cliente
                        @elseif($scenario == 'view')
                            Visualizar Cliente
                        @else
                            Novo Cliente
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-center">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Dados do Cliente</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $scenario == 'edit' ? route('clientes.update', $cliente->id) : route('clientes.store') }}" method="POST" id="clienteForm">
                        @csrf
                        @if($scenario == 'edit')
                            @method('PUT')
                        @endif
                        
                        <!-- Dados Pessoais -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-circle"></i> Dados Pessoais
                                </h6>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" 
                                       name="nome" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $cliente->nome : old('nome') }}"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $cliente->email : old('email') }}" 
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" 
                                       name="telefone" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $cliente->telefone : old('telefone') }}" 
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       placeholder="(00) 00000-0000"
                                       required>
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marker-alt"></i> Endereço
                                </h6>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" 
                                       class="form-control @error('cep') is-invalid @enderror" 
                                       id="cep" 
                                       name="cep" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $cliente->cep : old('cep') }}" 
                                       {{ $scenario == 'view' ? 'disabled' : '' }}
                                       placeholder="00000-000">
                                @error('cep')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" 
                                       class="form-control @error('cidade') is-invalid @enderror" 
                                       id="cidade" 
                                       name="cidade" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $cliente->cidade : old('cidade') }}"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}>
                                @error('cidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" 
                                        name="estado" 
                                        {{ $scenario == 'view' ? 'disabled' : '' }}>
                                    <option value="">UF</option>
                                    @php
                                        $selectedEstado = ($scenario == 'view' || $scenario == 'edit') ? $cliente->estado : old('estado');
                                        $estados = [
                                            'AC' => 'AC', 'AL' => 'AL', 'AP' => 'AP', 'AM' => 'AM', 'BA' => 'BA',
                                            'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES', 'GO' => 'GO', 'MA' => 'MA',
                                            'MT' => 'MT', 'MS' => 'MS', 'MG' => 'MG', 'PA' => 'PA', 'PB' => 'PB',
                                            'PR' => 'PR', 'PE' => 'PE', 'PI' => 'PI', 'RJ' => 'RJ', 'RN' => 'RN',
                                            'RS' => 'RS', 'RO' => 'RO', 'RR' => 'RR', 'SC' => 'SC', 'SP' => 'SP',
                                            'SE' => 'SE', 'TO' => 'TO'
                                        ];
                                    @endphp
                                    @foreach($estados as $sigla => $nome)
                                        <option value="{{ $sigla }}" {{ $selectedEstado == $sigla ? 'selected' : '' }}>
                                            {{ $sigla }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" 
                                       class="form-control @error('endereco') is-invalid @enderror" 
                                       id="endereco" 
                                       name="endereco" 
                                       value="{{ ($scenario == 'view' || $scenario == 'edit') ? $cliente->endereco : old('endereco') }}"
                                       {{ $scenario == 'view' ? 'disabled' : '' }}>
                                @error('endereco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($scenario != 'view')
                            <!-- Botões -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> 
                                            @if($scenario == 'edit')
                                                Atualizar Cliente
                                            @else
                                                Salvar Cliente
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
    // Máscara para CPF
    $('#cpf').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = value;
    });

    // Máscara para telefone
    $('#telefone').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        this.value = value;
    });

    // Máscara para CEP
    $('#cep').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        this.value = value;
    });

    // Buscar endereço pelo CEP (apenas se não estiver no modo visualização)
    @if($scenario != 'view')
    $('#cep').on('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            // Mostrar loading
            $('#cep').addClass('is-loading');
            
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        $('#endereco').val(data.logradouro);
                        $('#cidade').val(data.localidade);
                        $('#estado').val(data.uf);
                    } else {
                        alert('CEP não encontrado!');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                })
                .finally(() => {
                    $('#cep').removeClass('is-loading');
                });
        }
    });
    @endif

    // Validação do formulário
    $('#clienteForm').on('submit', function(e) {
        @if($scenario != 'view')
        let isValid = true;
        
        // Validar campos obrigatórios
        const requiredFields = ['nome', 'email', 'telefone'];
        requiredFields.forEach(field => {
            const element = $(`#${field}`);
            if (!element.val().trim()) {
                element.addClass('is-invalid');
                isValid = false;
            } else {
                element.removeClass('is-invalid');
            }
        });

        // Validar email
        const email = $('#email').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios corretamente.');
        }
        @endif
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

.is-loading {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='%23999' d='M10 3.5a6.5 6.5 0 1 0 6.5 6.5h-2a4.5 4.5 0 1 1-4.5-4.5V3.5z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.breadcrumb {
    background: none;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}
</style>
@endsection