<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', // Đã thêm vào fillable
        'name', 
        'description', 
        'price', 
        // 'stock', // Lưu ý: Trong cấu trúc DESCRIBE bạn gửi ở trên chưa có cột stock, 
                  // nếu thực tế có thì giữ lại, nếu không hãy xóa đi để tránh lỗi.
    ];

    // Thiết lập quan hệ với Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Thiết lập quan hệ với Hình ảnh
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * THÊM MỚI: Thiết lập quan hệ với Mô hình AR
     * Vì bạn đặt product_id ở bảng ar_models, nên đây là quan hệ HasOne hoặc HasMany.
     * Thường mỗi sản phẩm có 1 model AR chính, ta dùng hasOne để dễ gọi.
     */
    public function arModel()
    {
        return $this->hasOne(ARModel::class); 
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
