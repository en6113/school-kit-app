<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\Size;
use App\Models\ProductSize;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未ログインユーザーはカート操作ができず、ログイン画面にリダイレクトされる(): void
    {
        //Act&Assert
        $this->get(route('cart.index'))->assertRedirect(route('login'));
        $this->post(route('cart.add'))->assertRedirect(route('login'));
    }

    /** @test */
    public function ユーザーは新規の商品をカートに追加できる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create();

        //Act
        $response = $this->actingAs($user)->post(route('cart.add'),[
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        //Assert
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', ['user_id' => $user->id,]);
        $this->assertDatabaseHas('cart_details', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function 既にカートにある商品を追加した場合は数量が加算される(): void
    {
        //Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();

        $cartDetail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Act
        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Assert
        $this->assertEquals(3, $cartDetail->refresh()->quantity);
    }

    /** @test */
    public function ユーザーはスターターキットの商品をまとめてカートに追加できる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        //Act
        $response = $this->actingAs($user)->post(route('cart.add_kit'), [
            'products' => [
                ['product_id' => $productA->id,
                 'quantity' => 1],
                [
                'product_id' => $productB->id,
                 'quantity' => 2],
            ]
        ]);

        //Assert
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_details', ['product_id' => $productA->id, 'quantity' => 1]);
        $this->assertDatabaseHas('cart_details', ['product_id' => $productB->id, 'quantity' => 2]);
    }

    /** @test */
    public function ユーザーは自分のカート一覧を取得できる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        //Act
        $response = $this->actingAs($user)->get(route('cart.index'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewHas('cart');
    }

    /** @test */
    public function ユーザーは自分のカートの商品を削除できる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        $cartDetail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        //Act
        $response = $this->actingAs($user)
            ->from(route('cart.index'))
            ->delete(route('cart.destroy', $cartDetail));

        //Assert
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('cart_details', ['id' => $cartDetail->id]);
    }

    /** @test */
    public function 他人のカートの商品は削除できず403エラーになる(): void
    {
        //Arrange
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $cartB = Cart::factory()->create(['user_id' => $userB->id]);
        $product = Product::factory()->create();
        $cartDetailB = CartDetail::factory()->create([
            'cart_id' => $cartB->id,
            'product_id' => $product->id,
            ]);

        //Act
        $response = $this->actingAs($userA)->delete(route('cart.destroy', $cartDetailB));

        //Assert
        $response->assertStatus(403);
        $this->assertDatabaseHas('cart_details', ['id' => $cartDetailB->id]);
    }
}