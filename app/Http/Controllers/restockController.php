<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restock; // ✅ ini penting
use App\Http\Requests\RestockRequest;
use App\Models\Product; // ✅ Tambahkan ini
use Illuminate\Support\Facades\DB;


class restockController extends Controller
{

 public function get()
 {
     $data = Restock::join('products', 'products.id', '=', 'riwayat_stock.id_product')
         ->select(
             'riwayat_stock.*',
             'products.name as product_name'
         )
         ->latest('riwayat_stock.created_at')
         ->get();

     return response()->json([
         'success' => true,
         'data' => $data
     ]);
 }



  public function index(Request $request)
  {
      $per = $request->per ?? 10;
      $page = $request->page ? $request->page - 1 : 0;

      // Nomor urut
      DB::statement('SET @no = ' . ($page * $per));

      $data = DB::table('riwayat_stock')
          ->join('products', 'products.id', '=', 'riwayat_stock.id_product')
          ->select(
              'riwayat_stock.*',
              'products.name as product_name',
              DB::raw('@no := @no + 1 as no')
          )
          ->when($request->search, function ($query, $search) {
              $query->where('products.name', 'like', "%$search%")
                    ->orWhere('riwayat_stock.keterangan', 'like', "%$search%");
          })
          ->when($request->start_date && $request->end_date, function ($query) use ($request) {
              $query->whereBetween('riwayat_stock.created_at', [$request->start_date, $request->end_date]);
          })
          ->orderBy('riwayat_stock.created_at', 'desc')
          ->paginate($per);

      return response()->json($data);
  }

 public function store(RestockRequest $request)
 {
     $validatedData = $request->validated();

     // Ambil produk berdasarkan ID
     $product = Product::findOrFail($validatedData['id_product']);

     // Tambah atau kurangi stok tergantung tipe
     if ($validatedData['tipe'] === 'keluar') {
         if ($product->stock < 0) {
             return response()->json([
                 'success' => false,
                 'message' => 'Stok tidak mencukupi untuk pengurangan ini.'
             ], 400);
         }
     }

     $product->save();

     // Simpan ke riwayat stok
     $riwayat = Restock::create([
         'id_product' => $validatedData['id_product'],
         'tipe' => $validatedData['tipe'],
         'quantity' => $validatedData['quantity'],
     ]);

     return response()->json([
         'success' => true,
         'message' => 'Riwayat stok berhasil disimpan',
         'data' => $riwayat
     ]);
 }


}
