<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-header {
            background: #1a1a2e;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .invoice-header p {
            margin: 5px 0 0;
            opacity: 0.8;
        }
        .invoice-body {
            padding: 30px;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-info div p {
            margin: 5px 0;
        }
        .order-info strong {
            color: #1a1a2e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
        }
        .totals table {
            width: 300px;
            margin-left: auto;
        }
        .totals td {
            border: none;
            padding: 5px;
        }
        .totals .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            font-size: 12px;
            color: #6b7280;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .btn-print:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn-print no-print">🖨️ Print Invoice</button>
        <button onclick="window.close()" class="btn-print no-print" style="background: #6b7280; margin-left: 10px;">✖️ Close</button>
    </div>
    
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Ja Lanka</h1>
            <p>Global Flavors Mart</p>
        </div>
        
        <div class="invoice-body">
            <div class="order-info">
                <div>
                    <strong>Invoice To:</strong>
                    @if($order->shippingAddress)
                        <p>{{ $order->shippingAddress->full_name }}</p>
                        <p>{{ $order->shippingAddress->mobile }}</p>
                        <p>{{ $order->shippingAddress->address_line1 }}</p>
                        @if($order->shippingAddress->address_line2)
                            <p>{{ $order->shippingAddress->address_line2 }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->district }}</p>
                    @else
                        <p>No address on file</p>
                    @endif
                </div>
                <div style="text-align: right;">
                    <strong>Order Details:</strong>
                    <p>Order #: {{ $order->order_number }}</p>
                    <p>Date: {{ $order->created_at->format('F d, Y') }}</p>
                    <p>Payment: {{ ucfirst($order->payment_method) }}</p>
                    <p>Status: {{ ucfirst($order->order_status) }}</p>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th style="text-align: center">Qty</th>
                        <th style="text-align: right">Unit Price</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->product_sku }}</td>
                        <td style="text-align: center">{{ $item->quantity }}</td>
                        <td style="text-align: right">LKR {{ number_format($item->unit_price, 2) }}</td>
                        <td style="text-align: right">LKR {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="totals">
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        <td style="text-align: right">LKR {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td>Discount:</td>
                        <td style="text-align: right; color: #dc2626;">-LKR {{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Shipping:</td>
                        <td style="text-align: right">LKR {{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    @if($order->tax_amount > 0)
                    <tr>
                        <td>Tax:</td>
                        <td style="text-align: right">LKR {{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total">
                        <td><strong>Grand Total:</strong></td>
                        <td style="text-align: right"><strong>LKR {{ number_format($order->grand_total, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
            
            @if($order->notes)
            <div style="margin-top: 30px; padding: 15px; background: #f9fafb; border-radius: 6px;">
                <strong>Order Notes:</strong>
                <p style="margin: 5px 0 0; font-size: 14px;">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with Ja Lanka!</p>
            <p>For any inquiries, please contact us at info@jalanka.com or call +94 11 234 5678</p>
            <p>www.jalanka.com</p>
        </div>
    </div>
</body>
</html>