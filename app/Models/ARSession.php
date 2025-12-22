<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ar_model_id', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function arModel()
    {
        return $this->belongsTo(ARModel::class);
    }
}
