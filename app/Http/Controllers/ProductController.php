<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ARModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['images', 'arModel', 'category'])->get();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with(['images', 'arModel', 'category'])->findOrFail($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $newProduct = Product::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'category_id' => $request->category_id,
                    'description' => $request->description ?? 'Sản phẩm 3D'
                ]);

                if ($request->filled('image_url_manual')) {
                    ProductImage::create([
                        'product_id' => $newProduct->id,
                        'image_path' => $request->image_url_manual,
                        'is_primary' => 1
                    ]);
                }

                if ($request->filled('model_url_manual')) {
                    ARModel::create([
                        'product_id' => $newProduct->id,
                        'name' => $request->name . ' AR',
                        'model_path' => $request->model_url_manual
                    ]);
                }

                return response()->json($newProduct->load(['images', 'arModel']), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
