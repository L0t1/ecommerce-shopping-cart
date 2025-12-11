<?php

namespace App\Http\Controllers;

use App\Jobs\LowStockNotification;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Process the checkout and create an order.
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(): RedirectResponse
    {
        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        try {
            DB::transaction(function () use ($user, $cartItems) {
                // Calculate total
                $total = $cartItems->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                });

                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $total,
                    'status' => 'completed',
                ]);

                // Process each cart item
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

                    // Verify stock availability
                    if ($product->stock_quantity < $cartItem->quantity) {
                        throw ValidationException::withMessages([
                            'stock' => "Insufficient stock for {$product->name}. Only {$product->stock_quantity} items available.",
                        ]);
                    }

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $product->price, // Store current price
                    ]);

                    // Decrement stock
                    $product->decrementStock($cartItem->quantity);

                    // Check if product is now low stock and dispatch notification
                    $product->refresh(); // Refresh to get updated stock
                    if ($product->isLowStock()) {
                        LowStockNotification::dispatch($product);
                    }

                    // Delete cart item
                    $cartItem->delete();
                }
            });

            return redirect()
                ->route('order.confirmation')
                ->with('success', 'Order placed successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->route('cart.index')
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }
}
