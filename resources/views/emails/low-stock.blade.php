<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .product-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .stock-warning {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚠️ Low Stock Alert</h2>
        
        <div class="alert-box">
            <p>The following product is running low on stock and requires attention:</p>
        </div>

        <div class="product-info">
            <h3>{{ $product->name }}</h3>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p class="stock-warning">Current Stock: {{ $product->stock_quantity }} units</p>
            <p><strong>Low Stock Threshold:</strong> {{ $product->low_stock_threshold }} units</p>
        </div>

        <p>Please reorder this product to maintain adequate inventory levels.</p>
        
        <p>
            Best regards,<br>
            E-commerce System
        </p>
    </div>
</body>
</html>
