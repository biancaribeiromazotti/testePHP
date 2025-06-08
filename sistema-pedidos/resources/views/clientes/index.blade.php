@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users"></i> Clientes</h2>
    <div>
        <button class="btn btn-danger" id="delete-selected" style="display: none;">
            <i class="fas fa-trash"></i> Deletar Selecionados
        </button>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Cliente
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('clientes.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" 
                       placeholder="Buscar por nome ou email..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="sort_by" class="form-select">
                    <option value="">Ordenar por...</option>
                    <option value="nome" {{ request('sort_by') == 'nome' ? 'selected' : '' }}>Nome</option>
                    <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Data Cadastro</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort_direction" class="form-select">
                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Crescente</option>
                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Decrescente</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="per_page" class="form-select">
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 por página</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 por página</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 por página</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabela -->
<div class="card">
    <div class="card-body">
        <form id="bulk-delete-form">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $cliente->id }}" class="item-checkbox">
                            </td>
                            <td>{{ $cliente->nome }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->telefone ?? '-' }}</td>
                            <td>{{ $cliente->cidade ?? '-' }}</td>
                            <td>
                                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Tem certeza que deseja deletar o cliente?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum cliente encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- Paginação -->
<div class="d-flex justify-content-center mt-4">
    {{ $clientes->appends(request()->query())->links() }}
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#select-all').change(function() {
        $('.item-checkbox').prop('checked', this.checked);
        toggleDeleteButton();
    });
    
    $('.item-checkbox').change(function() {
        toggleDeleteButton();
    });
    
    function toggleDeleteButton() {
        const checkedItems = $('.item-checkbox:checked').length;
        if (checkedItems > 0) {
            $('#delete-selected').show();
        } else {
            $('#delete-selected').hide();
        }
    }
    
    // Bulk delete
    $('#delete-selected').click(function() {
        if (confirm('Tem certeza que deseja deletar os itens selecionados?')) {
            const ids = $('.item-checkbox:checked').map(function() {
                return this.value;
            }).get();
            
            $.ajax({
                url: '{{ route("clientes.destroy-multiple") }}',
                method: 'DELETE',
                data: {
                    ids: ids,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endsection