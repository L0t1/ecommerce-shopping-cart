<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create regular test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample products with varying stock levels
        $products = [
            [
                'name' => 'Laptop',
                'description' => 'High-performance laptop with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
                'stock_quantity' => 25,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking',
                'price' => 29.99,
                'stock_quantity' => 50,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with custom switches',
                'price' => 149.99,
                'stock_quantity' => 8, // Low stock
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Monitor 27"',
                'description' => '4K UHD Monitor with HDR support',
                'price' => 499.99,
                'stock_quantity' => 15,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C Hub with HDMI and Ethernet',
                'price' => 49.99,
                'stock_quantity' => 100,
                'low_stock_threshold' => 20,
            ],
            [
                'name' => 'Webcam HD',
                'description' => '1080p HD Webcam with built-in microphone',
                'price' => 79.99,
                'stock_quantity' => 5, // Low stock
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Headset',
                'description' => 'Noise-cancelling wireless headset',
                'price' => 199.99,
                'stock_quantity' => 30,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'External SSD 1TB',
                'description' => 'Portable SSD with USB 3.2 Gen 2',
                'price' => 129.99,
                'stock_quantity' => 40,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'Smartphone',
                'description' => 'Latest smartphone with 128GB storage',
                'price' => 799.99,
                'stock_quantity' => 20,
                'low_stock_threshold' => 8,
            ],
            [
                'name' => 'Tablet 10"',
                'description' => '10-inch tablet with stylus support',
                'price' => 449.99,
                'stock_quantity' => 12,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Smartwatch',
                'description' => 'Fitness tracking smartwatch with GPS',
                'price' => 249.99,
                'stock_quantity' => 35,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Wireless Charger',
                'description' => 'Fast wireless charging pad',
                'price' => 39.99,
                'stock_quantity' => 60,
                'low_stock_threshold' => 20,
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with 12-hour battery',
                'price' => 89.99,
                'stock_quantity' => 45,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'Gaming Controller',
                'description' => 'Wireless gaming controller with haptic feedback',
                'price' => 69.99,
                'stock_quantity' => 3, // Low stock
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Desk Lamp',
                'description' => 'LED desk lamp with adjustable brightness',
                'price' => 45.99,
                'stock_quantity' => 28,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Cable Management Box',
                'description' => 'Organize your cables with this sleek box',
                'price' => 19.99,
                'stock_quantity' => 75,
                'low_stock_threshold' => 25,
            ],
            [
                'name' => 'Phone Stand',
                'description' => 'Adjustable phone stand for desk',
                'price' => 14.99,
                'stock_quantity' => 90,
                'low_stock_threshold' => 30,
            ],
            [
                'name' => 'Laptop Stand',
                'description' => 'Aluminum laptop stand with cooling',
                'price' => 54.99,
                'stock_quantity' => 22,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Graphics Card',
                'description' => 'High-end graphics card for gaming',
                'price' => 899.99,
                'stock_quantity' => 6, // Low stock
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'RAM 16GB Kit',
                'description' => 'DDR4 16GB (2x8GB) RAM Kit 3200MHz',
                'price' => 79.99,
                'stock_quantity' => 33,
                'low_stock_threshold' => 10,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
