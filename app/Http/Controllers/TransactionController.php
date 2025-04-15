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
        $data = Transaction::select('transaction_code','id')
            // ->groupBy('transaction_code')
            ->latest()->paginate($per);

        $no = ($data->currentPage()-1) * $per + 1;
        foreach($data as $item){
            $item->no = $no++;  };
            // ->latest('created_at')
            // ->paginate($per);

        return response()->json($data);
    }

    public function store(TransactionRequest $request)
    {

        $transactionCode = 'TRX-' . strtoupper(Str::random(8));

        foreach ($request->all() as $item) {
            DB::table('transactions')->insert([
                'transaction_code' => $transactionCode,
                'id_product' => $item['id_product'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'sub_total' => $item['sub_total'],
                'total' => $item['total'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Transaksi disimpan']);
        // }
        // $product->syncPermissions($validatedData['permissions']);
        // return response()->json([
        //     'success' => true,
        //     'transaction' => $transaction,
        // ]);
    }

    public function show($id_transaksi)
    {
        $details =  Transaction_product::where('id_transaksi', $id_transaksi)
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



    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $validatedData = $request->validated();

        $transaction->update([
            // 'name' => $validatedData['name'],
            // 'guard_name' => 'api',
            'id_product' => $validatedData['id_product'],
            'price' => $validatedData['price'],
            'total' => $validatedData['total'],
            'quantity' => $validatedData['quantity'],
            'sub_total' => $validatedData['sub_total'],
            // 'permissions' => $product->permissions->pluck('name')
        ]);

        // $product->syncPermissions($validatedData['permissions']);

        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            'success' => true
        ]);
    }
}