<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Xem lịch sử đơn hàng của người dùng.
     * Đã cập nhật nạp quan hệ lồng nhau (items -> product -> images) 
     * để hiển thị ảnh thumbnail ở frontend.
     */
    public function index(Request $request)
    {
        return Order::with(['items.product.images'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * API QUAN TRỌNG: Lưu đơn hàng từ trang checkout.html.
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Tính tổng tiền thực tế từ mảng items gửi lên
                $totalPrice = collect($request->items)->sum(function($item) {
                    return $item['price'] * $item['quantity'];
                });

                // 2. Tạo đơn hàng (Order)
                // Lưu ý: Đảm bảo cột 'status' và 'payment_method' có giá trị mặc định trong DB hoặc code
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'total_price' => $totalPrice,
                    'status' => 'pending', // Mặc định là chờ xử lý
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);

                // 3. Lưu chi tiết đơn hàng (OrderItems) vào bảng liên kết
                foreach ($request->items as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return response()->json([
                    'message' => 'Đặt hàng thành công!',
                    'order_id' => $order->id
                ], 201);
            });
        } catch (\Exception $e) {
            // Trả về thông báo lỗi cụ thể nếu quá trình Transaction thất bại
            return response()->json(['message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Xem chi tiết một đơn hàng cụ thể (nếu cần).
     */
    public function show(Request $request, $id)
    {
        $order = Order::with(['items.product.images'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }
}
