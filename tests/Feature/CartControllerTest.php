<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Database\Factories\CartDetail;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザーはカートを作成（商品を追加）できる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $product = Product::factory()->count(3)->create();

        //Act
        $response = $this->actingAs($user)->post(route('carts.add'),[
            'product_id' => $product->id,
            'product_size_id' => $product->product_size_id,
            'quantity' => $product->quantity,
        ]);

        //Assert
        $response->assertRedirect(route('carts.index'));
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_size_id' => $product->product_size_id,
            'quantity' => $product->quantity,
        ]);
    }

    /** @test */
    public function ユーザーはカート一覧を取得できる(): void
    {
        // Arrange
        $user = User::factory()->create();
        CartDetail::factory()->count(3)->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)->get(route('carts.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('carts');
    }

    /** @test */
    public function ユーザーはカートを更新できる(): void
    {
        // Arrange
        $user = User::factory()->create();
        $cart = CartDetail::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->count(3)->create();

        // Act
        $response = $this->actingAs($user)->put(route('carts.update', $cart), [
            'product_id' => $product->id,
            'product_size_id' => $product->product_size_id,
            'quantity' => $product->quantity,
        ]);

        // Assert
        $response->assertRedirect(route('carts.index'));
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_size_id' => $product->product_size_id,
            'quantity' => $product->quantity,
        ]);
    }

    /** @test */
    public function ユーザーはカートを削除できる(): void
    {
        // Arrange
        $user = User::factory()->create();
        $cart = CartDetail::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)->delete(route('carts.remove', $cart));

        // Assert
        $response->assertRedirect(route('carts.index'));
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }
}
