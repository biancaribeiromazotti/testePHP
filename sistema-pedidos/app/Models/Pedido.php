<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'status',
        'total',
        'desconto',
        'observacoes',
        'data_pedido',
        'codigo'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'data_pedido' => 'datetime',
        'status' => 'string',     
        'observacoes' => 'string',
        'codigo' => 'string'      
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco_unitario', 'subtotal')
                    ->withTimestamps();
    }

    public function itemPedido()
    {
        return $this->hasMany(PedidoProduto::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['search'])) {
            $query->whereHas('cliente', function($q) use ($filters) {
                $q->where('codigo', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['sort_by']) && isset($filters['sort_direction'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction']);
        }

        return $query;
    }

    public function calcularTotal()
    {
        $subtotal = $this->produtos->sum('pivot.subtotal');
        $this->total = $subtotal - ($subtotal * ($this->desconto / 100));
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($pedido) {
            if (empty($pedido->codigo)) {
                $pedido->codigo = 'PED-' . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
                
                // Garantir que o código seja único
                while (static::where('codigo', $pedido->codigo)->exists()) {
                    $pedido->codigo = 'PED-' . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
                }
            }
        });
    }
}