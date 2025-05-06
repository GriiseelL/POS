<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Struk Pembayaran</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      font-size: 14px;
    }

    table {
      width: 100%;
    }

    td,
    th {
      padding: 4px;
      text-align: left;
    }

    .total {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <h3>Struk Pembayaran</h3>
  <p>Kode Transaksi: {{ $transaction_code }}</p>
  {{-- <p>Kasir: {{ $seller }}</p> --}}
  <hr>
  <table>
    <thead>
      <tr>
        <th>Barang</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($items['details'] as $item)
      <tr>
      <td>{{ $item['product']['name'] }}</td>
      <td>{{ $item['quantity'] }}</td>
      <td>Rp{{ number_format($item['product']['price'], 0, ',', '.') }}</td>
      <td>Rp{{ number_format($item['product']['price'] * $item['quantity'], 0, ',', '.') }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <hr>
  <p>Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
  <p>Tax (12%): Rp{{ number_format($tax, 0, ',', '.') }}</p>
  <p class="total">Total: Rp{{ number_format($subtotal+$tax, 0, ',', '.') }}</p>
  <p>Terima kasih telah berbelanja!</p>
</body>

</html>
