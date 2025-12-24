<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric'
        ]);

        $order = Order::findOrFail($request->order_id);

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $request->amount,
            'status' => 'paid'
        ]);

        $order->update(['status' => 'paid']);

        return response()->json($payment, 201);
    }
}
