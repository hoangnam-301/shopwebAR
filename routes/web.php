<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

// 1. Trang chủ
Route::get('/', function () {
    $path = base_path('../frontend/User_interface/index.html');
    return File::exists($path) ? File::get($path) : response("Missing Index.html", 404);
});

// 2. Catch-all cho các trang khác (cart.html, login.html...)
// Loại trừ tuyệt đối các đường dẫn bắt đầu bằng api hoặc v1
Route::get('/{any}', function () {
    $path = base_path('../frontend/User_interface/index.html');
    return File::exists($path) ? File::get($path) : response("Missing Frontend File", 404);
})->where('any', '^(?!api|v1).*$');
