<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     *
     * @return Response
     */
    public function index(): Response
    {
        $cartItems = auth()->user()->cartItems()
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return inertia('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock availability
        if ($product->stock_quantity < $validated['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock available. Only ' . $product->stock_quantity . ' items in stock.',
            ]);
        }

        DB::transaction(function () use ($validated, $product) {
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($cartItem) {
                // Update existing cart item
                $newQuantity = $cartItem->quantity + $validated['quantity'];
                
                if ($product->stock_quantity < $newQuantity) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Insufficient stock available. Only ' . $product->stock_quantity . ' items in stock.',
                    ]);
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                // Create new cart item
                CartItem::create([
                    'user_id' => auth()->id(),
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart item quantity.
     *
     * @param Request $request
     * @param CartItem $cartItem
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        // Ensure user owns this cart item
        if ($cartItem->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Check stock availability
        if ($cartItem->product->stock_quantity < $validated['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock available. Only ' . $cartItem->product->stock_quantity . ' items in stock.',
            ]);
        }

        $cartItem->update($validated);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart.
     *
     * @param CartItem $cartItem
     * @return RedirectResponse
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        // Ensure user owns this cart item
        if ($cartItem->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }
}
