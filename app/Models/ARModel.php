<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'file_path', 'thumbnail_path', 'description',
    ];

    public function sessions()
    {
        return $this->hasMany(ARSession::class);
    }
}
