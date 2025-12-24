<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Các thuộc tính có thể gán hàng loạt
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Các thuộc tính cần ẩn khi xuất JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính cần cast
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Quan hệ với cart
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
