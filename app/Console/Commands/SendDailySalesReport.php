<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReportMail;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get today's order items
        $orderItems = OrderItem::with('product')
            ->whereDate('created_at', today())
            ->get();

        // Group by product and calculate totals
        $salesData = $orderItems->groupBy('product_id')->map(function ($items) {
            $product = $items->first()->product;
            return [
                'name' => $product->name,
                'quantity' => $items->sum('quantity'),
                'revenue' => $items->sum(function ($item) {
                    return $item->quantity * $item->price;
                }),
            ];
        })->values();

        $totalRevenue = $salesData->sum('revenue');
        $totalItemsSold = $salesData->sum('quantity');

        // Get admin user
        $admin = User::where('email', 'admin@example.com')->first();

        if ($admin) {
            Mail::to($admin->email)->send(
                new DailySalesReportMail($salesData, $totalRevenue, $totalItemsSold, today())
            );

            $this->info('Daily sales report sent successfully!');
            return Command::SUCCESS;
        }

        $this->error('Admin user not found!');
        return Command::FAILURE;
    }
}
