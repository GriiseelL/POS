<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\Transaction_product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TransactionController extends Controller
{
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
        $data = Transaction::select('transaction_code', 'id')
            // ->groupBy('transaction_code')
            ->latest()->paginate($per);

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
        DB::beginTransaction();

        try {
            $transactionCode = 'TRX-' . strtoupper(Str::random(8));

            // Ambil item pertama untuk dapatkan total (misalnya semua item punya total/sub_total sama)
            $firstItem = $request->all()[0];

            // Simpan ke tabel transactions
            $transactionId = DB::table('transactions')->insertGetId([
                'transaction_code' => $transactionCode,
                'total' => $firstItem['total'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan masing-masing item ke transaction_products
            foreach ($request->all() as $item) {
                DB::table('transaction_product')->insert([
                    'id_transaksi' => $transactionId,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Transaksi disimpan']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan transaksi', 'details' => $e->getMessage()], 500);
        }
    }


    public function show($id_transaksi)
    {
        $details = Transaction_product::where('id_transaksi', $id_transaksi)
            ->join('products', 'transaction_product.id_product', '=', 'products.id')
            // ->where('transactions.transaction_code', $transaction_code)
            ->select(
                'transaction_product.*',
                'products.name as product_name',
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
}