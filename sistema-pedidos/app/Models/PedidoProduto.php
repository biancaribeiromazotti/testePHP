<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    use HasFactory;

    protected $table = 'pedido_produto';

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
        'subtotal'
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relacionamentos
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    // Boot method para calcular subtotal automaticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pedidoProduto) {
            $pedidoProduto->subtotal = $pedidoProduto->quantidade * $pedidoProduto->preco_unitario;
        });
    }

    // Accessor para formatar o subtotal
    public function getSubtotalFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->subtotal, 2, ',', '.');
    }

    // Accessor para formatar o preço unitário
    public function getPrecoUnitarioFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->preco_unitario, 2, ',', '.');
    }
}