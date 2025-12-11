<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Response;

class ProductManagementController extends Controller
{
    /**
     * Display product management page.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show create product form.
     */
    public function create(): Response
    {
        return inertia('Admin/Products/Create');
    }

    /**
     * Store a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:1'],
        ]);

        // Set default threshold if not provided
        if (!isset($validated['low_stock_threshold'])) {
            $validated['low_stock_threshold'] = 10;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show edit product form.
     */
    public function edit(Product $product): Response
    {
        return inertia('Admin/Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:1'],
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Check if product has existing orders
        if ($product->orderItems()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete product with existing orders.');
        }

        // Delete associated cart items first
        $product->cartItems()->delete();
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
