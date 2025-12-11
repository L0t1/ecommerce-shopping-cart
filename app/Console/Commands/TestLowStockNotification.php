<?php

namespace App\Console\Commands;

use App\Jobs\LowStockNotification;
use App\Models\Product;
use Illuminate\Console\Command;

class TestLowStockNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test low stock notification email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get or create a test product with low stock
        $product = Product::first();

        if (!$product) {
            $product = Product::create([
                'name' => 'Test Product',
                'description' => 'A test product for low stock notification',
                'price' => 29.99,
                'stock_quantity' => 5,
                'low_stock_threshold' => 10,
            ]);
            $this->info('Created test product with low stock.');
        } else {
            // Update existing product to have low stock
            $product->update([
                'stock_quantity' => 5,
                'low_stock_threshold' => 10,
            ]);
            $this->info("Updated product '{$product->name}' to have low stock.");
        }

        // Dispatch the low stock notification job
        LowStockNotification::dispatch($product);

        $this->info('Low stock notification job dispatched!');
        $this->info('Check your Mailtrap inbox for the email.');
        
        return Command::SUCCESS;
    }
}
