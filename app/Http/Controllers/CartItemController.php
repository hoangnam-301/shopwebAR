<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $item = $cart->items()->updateOrCreate(
            ['product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        $item->update($request->only('quantity'));
        return response()->json($item);
    }

    public function destroy($id)
    {
        CartItem::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
