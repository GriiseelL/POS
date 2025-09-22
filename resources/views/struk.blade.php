<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body { 
            font-family: 'Courier New', monospace; 
            font-size: 12px; 
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            max-width: 300px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .company-name { 
            font-weight: bold; 
            font-size: 16px; 
            margin-bottom: 5px;
        }
        .transaction-info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .items-table { 
            width: 100%; 
            margin-bottom: 15px;
        }
        .items-table th {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            text-align: left;
            font-size: 11px;
        }
        .items-table td { 
            padding: 3px 0;
            font-size: 11px;
        }
        .item-name { 
            width: 60%; 
        }
        .item-qty { 
            width: 15%; 
            text-align: center;
        }
        .item-price { 
            width: 25%; 
            text-align: right;
        }
        .totals { 
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 5px;
        }
        .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 10px;
        }
        .payment-info {
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            padding: 8px;
            background-color: #f0f0f0;
        }
        .footer { 
            text-align: center; 
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">TOKO SAYA</div>
        <div>Jl. Contoh No. 123</div>
        <div>Telp: 081234567890</div>
    </div>

    <div class="transaction-info">
        <div>No. Transaksi: {{ $transaction_code }}</div>
        <div>Tanggal: {{ date('d/m/Y H:i:s') }}</div>
        <div>Kasir: {{ $seller }}</div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="item-name">Item</th>
                <th class="item-qty">Qty</th>
                <th class="item-price">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items['details'] as $item)
            <tr>
                <td class="item-name">{{ $item['product']['name'] }}</td>
                <td class="item-qty">{{ $item['quantity'] }}</td>
                <td class="item-price">{{ number_format($item['product']['price'] * $item['quantity'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Pajak (12%):</span>
            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
        </div>
        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment-info">
        METODE PEMBAYARAN: {{ $payment_display ?? 'TUNAI' }}
        @if(isset($payment_method) && $payment_method !== 'cash')
            <br><small>Status: LUNAS</small>
        @endif
    </div>

    <div class="footer">
        <div>Terima Kasih Atas Kunjungan Anda!</div>
        <div>{{ date('d/m/Y H:i:s') }}</div>
        <div style="margin-top: 10px;">
            ================================
        </div>
        <div style="font-size: 10px;">
            Barang yang sudah dibeli tidak dapat dikembalikan
        </div>
    </div>
</body>
</html>