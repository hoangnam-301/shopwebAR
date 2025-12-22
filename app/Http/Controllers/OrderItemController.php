<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item = OrderItem::findOrFail($id);
        $item->update($request->only('quantity'));

        return response()->json($item);
    }

    public function destroy($id)
    {
        OrderItem::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
