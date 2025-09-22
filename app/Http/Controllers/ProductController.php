<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function get(Request $request)
    {

    $query = Product::with('category');

        if ($request->has('id_category')) {
            $query->where('id_category', $request->id_category);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),// Menampilkan kategori
        ]);
    }

    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Product::with('category') // Ambil kategori juga
            ->when($request->search, function (Builder $query, string $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function store(ProductRequest $request)
    {

        // Validasi dulu
        $validatedData = $request->validated();

        // Upload file jika ada
        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('photo', 'public');
        } else {
            $validatedData['photo'] = null; // Optional: set null jika tidak ada file
        }

        // Simpan data ke DB
        $product = Product::create([
            'name' => $validatedData['name'],
            'id_category' => $validatedData['id_category'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'photo' => $validatedData['photo'] ?? null, // jaga-jaga kalau kosong
        ]);

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }


    public function show(Product $product)
    {
        $product->load('category'); // Memastikan kategori ikut diambil

        return response()->json([
            'product' => [
                'name' => $product->name,
                'id_category' => $product->id_category, // Tambahkan ini
                'category' => $product->category, // Sekarang kategori pasti muncul
                'price' => $product->price,
                'stock' => $product->stock,
                'photo' => $product->photo,
            ]
        ]);
    }


    // public function update(ProductRequest $request, Product $product)
    // {
    //     $validatedData = $request->validated();


    //      unset($validatedData['stock']);

    //     if ($request->hasFile('photo')) {
    //         // Hapus foto lama kalau ada
    //         if ($product->photo && \Storage::exists('public/' . $product->photo)) {
    //             \Storage::delete('public/' . $product->photo);
    //         }

    //         // Simpan foto baru
    //         $path = $request->file('photo')->store('photo', 'public');
    //         $validatedData['photo'] = $path;
    //     } else {
    //         // Kalau tidak ada file baru dikirim, tetap pakai yang lama
    //         unset($validatedData['photo']);
    //     }

    //     $product->update([
    //         'name' => $validatedData['name'],
    //         'id_category' => $validatedData['id_category'],
    //         'price' => $validatedData['price'],
    //         'photo' => $validatedData['photo'],
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'product' => $product->load('category') // Pastikan kategori ikut dimuat setelah update
    //     ]);
    // }


    public function update(ProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        unset($validatedData['stock']); // Remove stock from validated data

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($product->photo && \Storage::exists('public/' . $product->photo)) {
                \Storage::delete('public/' . $product->photo);
            }
            // Store new photo
            $path = $request->file('photo')->store('photo', 'public');
            $validatedData['photo'] = $path;
        } else {
            // If no new file uploaded, keep the existing photo
            $validatedData['photo'] = $product->photo; // Keep existing photo
            // Don't unset here because we need it for the update
        }

        $product->update([
            'name' => $validatedData['name'],
            'id_category' => $validatedData['id_category'],
            'price' => $validatedData['price'],
            'photo' => $validatedData['photo'], // This will now always have a value
        ]);

        return response()->json([
            'success' => true,
            'product' => $product->load('category')
        ]);
    }
    
    public function destroy(Product $product)
    {

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }
        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function first($id)
    {
        $product = Product::where('id', $id)->first();

        return response()->json([
            'product' => $product
        ]);
    }
}