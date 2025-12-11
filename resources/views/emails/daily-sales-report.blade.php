<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .total-row {
            font-weight: bold;
            background-color: #e8f5e9;
        }
        .no-sales {
            padding: 20px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📊 Daily Sales Report</h2>
            <p>{{ $date->format('F d, Y') }}</p>
        </div>

        @if($salesData->isEmpty())
            <div class="no-sales">
                <p>No sales recorded for {{ $date->format('F d, Y') }}</p>
            </div>
        @else
            <div class="summary">
                <h3>Summary</h3>
                <p><strong>Total Items Sold:</strong> {{ number_format($totalItemsSold) }}</p>
                <p><strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}</p>
            </div>

            <h3>Sales Breakdown</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData as $sale)
                        <tr>
                            <td>{{ $sale['name'] }}</td>
                            <td>{{ number_format($sale['quantity']) }}</td>
                            <td>${{ number_format($sale['revenue'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Total</td>
                        <td>{{ number_format($totalItemsSold) }}</td>
                        <td>${{ number_format($totalRevenue, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <p>
            Best regards,<br>
            E-commerce System
        </p>
    </div>
</body>
</html>
