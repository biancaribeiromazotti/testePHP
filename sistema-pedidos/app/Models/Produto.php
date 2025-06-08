<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'estoque',
        'categoria'
    ];

    protected $casts = [
        'preco' => 'decimal:2'
    ];

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco_unitario', 'subtotal')
                    ->withTimestamps();
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('nome', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('categoria', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['sort_by']) && isset($filters['sort_direction'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction']);
        }

        return $query;
    }
}