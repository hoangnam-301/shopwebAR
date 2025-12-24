<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // 1. Phải thêm các cột mới vào $fillable thì Laravel mới cho phép lưu
    protected $fillable = [
        'user_id', 
        'total_price', 
        'status', 
        'phone', 
        'address', 
        'payment_method'
    ];

    // 2. Định nghĩa quan hệ để lệnh $order->items() trong Controller hoạt động
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
