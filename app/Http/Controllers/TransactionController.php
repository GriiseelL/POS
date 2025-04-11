<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
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
        $data = Transaction::select('transaction_code', DB::raw('MAX(created_at) as created_at'), DB::raw('@no := @no + 1 AS no'))
            ->groupBy('transaction_code')
            ->latest('created_at')
            ->paginate($per);
        ;

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

    public function show(Transaction $transaction)
    {

        $transaction->load('product');
        // $product = Product::find($id);
        return response()->json([
            'transaction' => [
                // 'transaction_code' => $transactionCode,
                // 'name' => $transaction->name,
                'id_product' => $transaction->id_product,
                'price' => $transaction->price,
                'total' => $transaction->total,
                'sub_total' => $transaction->sub_total,
                'quantity' => $transaction->quantity,
            ]
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