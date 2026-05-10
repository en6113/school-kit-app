<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartDetail;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //CartSeeder
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);

            CartDetail::factory()->count(rand(1,5))->create([
                'cart_id' => $cart->id,
            ]);
        }
    }
}
