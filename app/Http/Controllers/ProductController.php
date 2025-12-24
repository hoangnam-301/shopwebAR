<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ArModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Lấy toàn bộ sản phẩm kèm ảnh và AR models
    public function index()
    {
        $products = Product::with(['images', 'arModels', 'category'])->get();
        return response()->json($products);
    }

    // Lấy chi tiết một sản phẩm
    public function show($id)
    {
        $product = Product::with(['images', 'arModels', 'category'])->findOrFail($id);
        return response()->json($product);
    }

    // Thêm sản phẩm mới kèm upload ảnh và AR model
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'required|file|mimes:jpg,jpeg,png',
            'ar_model_file' => 'required|file|mimes:glb',
            'ar_model_name' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($validated, $request, &$product) {
            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null
            ]);

            // Upload ảnh chính
            $imagePath = $request->file('image')->store('public/products');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => Storage::url($imagePath),
                'is_primary' => 1
            ]);

            // Upload AR model
            $modelPath = $request->file('ar_model_file')->store('public/ar_models');
            ArModel::create([
                'product_id' => $product->id,
                'model_name' => $validated['ar_model_name'],
                'file_path' => Storage::url($modelPath),
                'is_active' => 1
            ]);
        });

        return response()->json($product, 201);
    }

    // Cập nhật sản phẩm kèm ảnh và AR model
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
            'ar_model_file' => 'nullable|file|mimes:glb',
            'ar_model_name' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($product, $validated, $request) {
            $product->update($validated);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/products');
                // Cập nhật ảnh chính
                $primaryImage = $product->images()->where('is_primary', 1)->first();
                if ($primaryImage) {
                    $primaryImage->update(['image_path' => Storage::url($imagePath)]);
                } else {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => Storage::url($imagePath),
                        'is_primary' => 1
                    ]);
                }
            }

            if ($request->hasFile('ar_model_file')) {
                $modelPath = $request->file('ar_model_file')->store('public/ar_models');
                $arModel = $product->arModels()->first();
                if ($arModel) {
                    $arModel->update([
                        'file_path' => Storage::url($modelPath),
                        'model_name' => $validated['ar_model_name'] ?? $arModel->model_name
                    ]);
                } else {
                    ArModel::create([
                        'product_id' => $product->id,
                        'model_name' => $validated['ar_model_name'] ?? 'AR Model',
                        'file_path' => Storage::url($modelPath),
                        'is_active' => 1
                    ]);
                }
            }
        });

        return response()->json($product);
    }

    // Xóa sản phẩm kèm ảnh và AR model
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::transaction(function () use ($product) {
            $product->images()->delete();
            $product->arModels()->delete();
            $product->delete();
        });

        return response()->json(null, 204);
    }
}
