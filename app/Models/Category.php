<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'is_active'];

    // Quan hệ: Một danh mục có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Quan hệ: Danh mục cha (nếu có)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
