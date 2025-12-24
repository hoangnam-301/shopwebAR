<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    $path = '/var/www/frontend/User_interface/index.html'; 
    
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    
    return response("Không tìm thấy file Frontend tại: " . $path, 404);
})->where('any', '.*');

