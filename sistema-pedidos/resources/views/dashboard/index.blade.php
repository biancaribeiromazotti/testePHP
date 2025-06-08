@extends('layouts.app')

@section('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.125rem;
}

.fa-4x {
    font-size: 4em;
}
</style>
@endsection

@section('content')
<div class="container">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1><i class="fas fa-tachometer-alt text-primary"></i> Dashboard</h1>
        <p class="text-muted">Sistema de Gestão - Painel Principal</p>
    </div>

    <!-- Cards de Navegação -->
    <div class="row justify-content-center">
        <!-- Clientes -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-4x text-primary"></i>
                    </div>
                    <h4 class="card-title text-primary">Clientes</h4>
                    <p class="card-text text-muted mb-4">
                        Gerencie seus clientes, cadastre novos e mantenha as informações atualizadas.
                    </p>
                    <a href="{{ route('clientes.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-right"></i> Acessar Clientes
                    </a>
                </div>
            </div>
        </div>

        <!-- Produtos -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-box fa-4x text-success"></i>
                    </div>
                    <h4 class="card-title text-success">Produtos</h4>
                    <p class="card-text text-muted mb-4">
                        Controle seu estoque, cadastre produtos e gerencie preços e categorias.
                    </p>
                    <a href="{{ route('produtos.index') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-arrow-right"></i> Acessar Produtos
                    </a>
                </div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shopping-cart fa-4x text-info"></i>
                    </div>
                    <h4 class="card-title text-info">Pedidos</h4>
                    <p class="card-text text-muted mb-4">
                        Acompanhe pedidos, gerencie vendas e controle o status das entregas.
                    </p>
                    <a href="{{ route('pedidos.index') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-arrow-right"></i> Acessar Pedidos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

