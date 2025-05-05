<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\Transaction_product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
// use Xendit\Xendit;
// use Xendit\Invoice;
// use Xendit\Services\InvoiceService;
// use Xendit\Invoice\Invoice;

class TransactionController extends Controller
{

    public function invoice(Request $request)
    {
        Configuration::setXenditKey(env('XENDIT_SECRET'));

        $apiInstance = new InvoiceApi();
        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => $request->external_id, // dari generateTransactionCode()
            'description' => $request->description,
            'amount' => $request->amount,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1
        ]); // \Xendit\Invoice\CreateInvoiceRequest
        // $for_user_id = "62efe4c33e45694d63f585f0"; // string | Business ID of the sub-account merchant (XP feature)

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            return response()->json($result);
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }
    // sesuaikan dengan SDK-mu
    // use Illuminate\Support\Facades\Http;

    public function createXendit(TransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. Simpan transaksi seperti biasa
            $transactionCode = 'TRX-' . strtoupper(Str::random(8));
            $data = $request->validated();
            $items = $data['items'];
            $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'total' => $total,
                'metode_pembayaran' => $data['metode_pembayaran'],
            ]);

            foreach ($items as $item) {
                $prod = DB::table('products')->find($item['id_product']);
                if (!$prod || $prod->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Stok produk habis'], 400);
                }
                Transaction_product::create([
                    'id_transaksi' => $transaction->id,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['quantity'],
                ]);
                DB::table('products')->where('id', $item['id_product'])
                    ->update(['stock' => $prod->stock - $item['quantity']]);
            }

            DB::commit();

            // 2. Panggil Xendit API tanpa SDK
            $response = Http::withBasicAuth(env('XENDIT_SECRET'), '')
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $transactionCode,
                    'amount' => $total,
                    'description' => 'Pembayaran transaksi #' . $transactionCode,
                    'invoice_duration' => 3600,
                    'currency' => 'IDR',
                    'success_redirect_url' => env('APP_URL')
                        . '/dashboard/transaction?code='
                        . $transactionCode
                        . '&print=true',
                ]);

            if ($response->failed()) {
                // rollback kalau API Xendit error
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat invoice di Xendit: ' . $response->body(),
                ], 500);
            }

            $invoice = $response->json(); // array hasil respons Xendit

            // 3. Kembalikan URL pembayaran
            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                // 'transaction_code' => $transactionCode,    // ← tambahkan ini
                'payment_url' => $invoice['invoice_url'],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Transaction::all()
        ]);
    }

    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        // $data = Transaction::with('product') // Ambil kategori juga
        //     ->when($request->search, function (Builder $query, string $search) {
        //         $query->where('price', 'like', "%$search%");
        //         $query->where('total', 'like', "%$search%");
        //         $query->where('sub_total', 'like', "%$search%");
        //         $query->where('quantity', 'like', "%$search%");
        //     })
        //     ->latest()
        //     ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);
        $data = Transaction::select('id', 'transaction_code')
            ->when($request->search, function ($query, $search) {
                $query->where('transaction_code', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per);

        $no = ($data->currentPage() - 1) * $per + 1;
        foreach ($data as $item) {
            $item->no = $no++;
        }
        ;
        // ->latest('created_at')
        // ->paginate($per);

        return response()->json($data);
    }

    public function indexpdf()
    {
        $data = Transaction::with('details.product')
            ->select('id', 'transaction_code', 'total', 'created_at')
            ->get();

        return view('laporan', ['transactions' => $data]);
    }

    public function store(TransactionRequest $request)
    {
        // return response()->json($request, 500);


        DB::beginTransaction();

        try {
            $transactionCode = 'TRX-' . strtoupper(Str::random(8));
            $data = $request->validated();
            $items = $data['items'];

            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $taxrate = 0.12;
            $tax = $subtotal * $taxrate;
            $total = $tax + $subtotal;


            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'total' => $total,
                'metode_pembayaran' => $data['metode_pembayaran'],
                // 'seller' => $data['seller'],   // kalau memang ada
            ]);


            foreach ($items as $item) {
                // Cek stok dulu
                $prod = DB::table('products')->where('id', $item['id_product'])->first();
                if (!$prod || $prod->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Stok produk habis'], 400);
                }

                $transaction_product = Transaction_product::create([
                    'id_transaksi' => $transaction->id,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('products')->where('id', $item['id_product'])->update([
                    'stock' => $prod->stock - $item['quantity'],
                ]);

            }
            DB::commit();

            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                $transaction_product,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }



        //     $firstItem = $request->all()[0];

        //     $transactionId = DB::table('transactions')->insertGetId([
        //         'transaction_code' => $transactionCode,
        //         // 'total' => $firstItem['total'],
        //         'metode_pembayaran' => $firstItem['metode_pembayaran'],
        //         'seller' => $firstItem['seller'],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        //     foreach ($request->all() as $item) {
        //         // Cek stok dulu
        //         $product = DB::table('products')->where('id', $item['id_product'])->first();
        //         if (!$product || $product->stock < $item['quantity']) {
        //             DB::rollBack();
        //             return response()->json([
        //                 'message' => 'Stok produk habis'
        //             ], 400);
        //         }

        //         // Simpan detail transaksi
        //         DB::table('transaction_product')->insert([
        //             'id_transaksi' => $transactionId,
        //             'id_product' => $item['id_product'],
        //             'quantity' => $item['quantity'],
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);

        //         // Kurangi stok
        //         DB::table('products')->where('id', $item['id_product'])->update([
        //             'stock' => $product->stock - $item['quantity'],
        //         ]);
        //     }

        //     DB::commit();
        //     return response()->json(['message' => 'Transaksi disimpan']);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        //     ], 500);
        // }

    }


    public function show($id_transaksi)
    {
        $details = Transaction_product::where('id_transaksi', $id_transaksi)
            ->join('products', 'transaction_product.id_product', '=', 'products.id')
            ->join('transactions', 'transaction_product.id_transaksi', '=', 'transactions.id') // ✅ join ke transaksi
            ->select(
                'transaction_product.*',
                'products.name as product_name',
                'transactions.metode_pembayaran as metode_pembayaran',
                // 'transactions.seller as seller',
                'products.price as product_price'
            )
            ->get();

        return response()->json([
            'transactions' => $details
        ]);
    }


    public function download_pdf()
    {
        $mpdf = new \Mpdf\Mpdf();

        $data = Transaction::with('details.product')
            ->select('id', 'transaction_code', 'total', 'created_at')
            ->get();

        $html = view('laporan', ['transactions' => $data])->render();

        $mpdf->WriteHTML($html);
        $mpdf->Output('laporan-penjualan.pdf', 'D'); // "D" = langsung download
    }

    // Di Controller Laravel
    public function generateReceipt(Request $request)
    {
        $data = $request->all(); // data struk
        return view('struk', $data)->render(); // kirim HTML string
    }

    public function byCode($code)
    {
        $transactions = Transaction::with('product')
            ->where('transaction_code', $code)
            ->get();

        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $first = $transactions->first();

        return response()->json([
            'transaction_code' => $code,
            'seller' => $first->seller,
            'items' => $transactions->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'sub_total' => $item->sub_total,
                ];
            }),
            'subtotal' => $first->total - $first->tax,
            'tax' => $first->tax,
            'total' => $first->total,
        ]);
    }
}
    
    