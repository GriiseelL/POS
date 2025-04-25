<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Transaksi</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      margin: 20px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h2 {
      margin: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 6px 8px;
      text-align: left;
    }

    th {
      background-color: #f5f5f5;
    }

    .text-right {
      text-align: right;
    }

    .footer {
      margin-top: 30px;
      font-size: 10px;
      text-align: right;
    }

    .transaction-block {
      margin-bottom: 30px;
      page-break-inside: avoid;
    }

    .item-table {
      margin-top: 5px;
    }
  </style>
</head>

<body>


  <div class="header">
    <h1>TOKO MOBIL CALAEL</h1>
    <h2>Laproan Transaksi</h2>
    <small>{{ \Carbon\Carbon::now()->format('d M Y H:i') }}</small>
  </div>

  @foreach ($transactions as $index => $trx)
    <div class="transaction-block">
    <table>
      <tr>
      <td><strong>No</strong></td>
      <td>{{ $index + 1 }}</td>
      </tr>
      <tr>
      <td><strong>Kode Transaksi</strong></td>
      <td>{{ $trx->transaction_code }}</td>
      </tr>
      <tr>
      <td><strong>Tanggal</strong></td>
      <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</td>
      </tr>
      <tr>
      <td><strong>Total</strong></td>
      <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
      </tr>
    </table>

    <table class="item-table">
      <thead>
      <tr>
        <th>Nama Produk</th>
        <th>Qty</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Subtotal</th>
        <th class="text-right">Pajak</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($trx->details as $item)
      <tr>
      <td>{{ $item->product->name}}</td>
      <td>{{ $item->quantity }}</td>
      <td class="text-right">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
      <td class="text-right">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
      <td class="text-right">Rp {{ number_format($item->product->price * 0.12, 0, ',', '.') }}</td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
  @endforeach
  @php
  $grandTotal = $transactions->sum('total');
  @endphp

  <table>
    <tr>
      <th style="text-align: left;">Total Seluruh Transaksi</th>
      <th style="text-align: right;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
    </tr>
  </table>

  <div class="footer">
    <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
  </div>
</body>

</html>