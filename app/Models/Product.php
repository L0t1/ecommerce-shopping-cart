<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
    ];

    /**
     * Get the cart items for this product.
     */
    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for this product.
     */
    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if product stock is low.
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Decrement product stock by given quantity.
     *
     * @throws \Exception
     */
    public function decrementStock(int $quantity): void
    {
        if ($this->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for product: {$this->name}");
        }

        $this->decrement('stock_quantity', $quantity);
    }

    /**
     * Increment product stock by given quantity.
     */
    public function incrementStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
    }
}
