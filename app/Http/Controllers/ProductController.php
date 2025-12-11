<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $products = \App\Models\Product::query()
            ->orderBy('name')
            ->get();

        return inertia('Products/Index', [
            'products' => $products,
        ]);
    }
}
