<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARModel extends Model
{
    use HasFactory;

    // Chỉ định bảng nếu tên bảng không theo chuẩn số nhiều của Laravel
    protected $table = 'ar_models';

    protected $fillable = [
        'product_id', 
        'name', 
        'model_path'
    ];

    // Quan hệ ngược: Mô hình AR này thuộc về sản phẩm nào
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
