<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{

    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Category::all()
        ]);
    }

    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Category::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%");
                // ->orWhere('category', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create([
            'name' => $validatedData['name'],
            // 'guard_name' => 'api',
            // 'category' => $validatedData['category']
        ]);
        // $product->syncPermissions($validatedData['permissions']);
        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }

    public function show(Category $category)
    {
        // $product = Product::find($id);
        return response()->json([
            'category' => [
                'name' => $category->name,
            ]
        ]);
    }

     public function update(CategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();


        $category->update([
            'name' => $validatedData['name'],
            // 'guard_name' => 'api',
            // 'category' => $validatedData['category'],
            // 'permissions' => $product->permissions->pluck('name')
        ]);

        // $product->syncPermissions($validatedData['permissions']);
        
        return response()->json([
            'success' => true,
            'product' => $category
        ]);
    }

    public function destroy(Category $category)
    {

        if ($category->photo) {
            Storage::disk('public')->delete($category->photo);
        }
        
        $category->delete();

        return response()->json([
            'success' => true
        ]);
    }
}