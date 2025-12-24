<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        return $product->images;
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'url' => 'required|string'
        ]);

        $product = Product::findOrFail($productId);

        $image = $product->images()->create([
            'url' => $request->url
        ]);

        return response()->json($image, 201);
    }

    public function destroy($productId, $id)
    {
        $product = Product::findOrFail($productId);
        $product->images()->findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
