<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use App\Models\Token;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    { 
        $this->call(UserRoleSeeder::class);
    }
}
