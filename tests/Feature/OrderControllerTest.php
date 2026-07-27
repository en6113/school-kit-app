<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Cache;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未ログインユーザーは注文操作ができず、ログイン画面にリダイレクトされる(): void
    {
        //Act&Assert
        $this->get(route('orders.index'))->assertRedirect(route('login'));
        $this->get(route('orders.show', 1))->assertRedirect(route('login'));
        $this->post(route('orders.store'))->assertRedirect(route('login'));
    }

    /** @test */
    public function ユーザーは注文を確定できる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id,]);
        $cartDetail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_size_id' => null,
            'quantity' => 1,
        ]);

        //Act
        $response = $this->actingAs($user)->post(route('orders.store'),[
            'idempotency_key' => 'test-key-123',//二重送信防止
            'delivery_method' => 1,
            'payment_method' => 1,
        ]);

        //Assert
        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', ['user_id' => $user->id,]);
        $this->assertDatabaseHas('order_details', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function 在庫が不足している場合、ユーザーは注文を確定できない(): void
    {
        //Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['total_stock' => 0]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartDetail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_size_id' => null,
            'quantity' => 1,
        ]);

        //Act
        $response = $this->actingAs($user)->post(route('orders.store'), [
            'idempotency_key' => 'test-key-123',//二重送信防止
            'delivery_method' => 1,
            'payment_method' => 1,
        ]);

        //Assert
        $response->assertStatus(400);
        $this->assertDatabaseMissing('orders', ['user_id' => $user->id]);
    }

    /** @test */
    public function カートが空の場合、ユーザーは注文を確定できない(): void
    {
        //Arrange
        $user = User::factory()->create();

        //Act
        $response = $this->actingAs($user)->post(route('orders.store'), [
            'idempotency_key' => 'test-key-123',//二重送信防止
            'delivery_method' => 1,
            'payment_method' => 1,
        ]);

        //Assert
        $response->assertStatus(400);
    }

    /** @test */
    public function ユーザーが二重送信してしまった場合、エラーになる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $idempotencyKey = 'test-key-123';
        Cache::put("order_processing_{$idempotencyKey}", true, 60);

        //Act
        $response = $this->actingAs($user)->post(route('orders.store'), [
            'idempotency_key' => $idempotencyKey,
            'delivery_method' => 1,
            'payment_method' => 1,
        ]);

        //Assert
        $response->assertStatus(429);
        $response->assertJson(['message' => '現在処理中です']);
        $this->assertDatabaseMissing('orders', ['user_id' => $user->id]);
    }

    /** @test */
    public function ユーザーは自分の注文履歴を見ることができる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total_amount' => 1000,
            'delivery_method' => 2,
            'payment_method' => 1,
            'status' => 1,
        ]);

        //Act
        $response = $this->actingAs($user)->get(route('orders.index'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    /** @test */
    public function ユーザーは自分の注文履歴の詳細を見ることができる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();

        OrderDetail::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_size_id' => null,
            'quantity' => 1,
            'price_at_purchase' => 1000,
        ]);

        //Act
        $response = $this->actingAs($user)->get(route('orders.show', $order));

        //Assert
        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    /** @test */
    public function ユーザーは自分の注文を削除することができる(): void
    {
        //Arrange
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $product = Product::factory()->create(['total_stock' => 49]);

        OrderDetail::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_size_id' => null,
            'quantity' => 1,
            'price_at_purchase' => 1000,
        ]);

        //Act
        $response = $this->actingAs($user)->delete(route('orders.cancel', $order));

        //Assert
        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 0,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'total_stock' => 50,
        ]);
    }

    /** @test */
    public function 発送済みの注文はキャンセルできない(): void
    {
        //Arrange
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 2,//発送済み
        ]);
        $product = Product::factory()->create();

        OrderDetail::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_size_id' => null,
            'quantity' => 1,
            'price_at_purchase' => 1000,
        ]);

        //Act
        $response = $this->actingAs($user)->delete(route('orders.cancel', $order));

        //Assert
        $response->assertSessionHas('error', '対象のご注文は発送済みのためキャンセルできません。');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 2,
        ]);
    }

    /** @test */
    public function 他人の注文は削除できず403エラーになる(): void
    {
        //Arrange
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $orderB = Order::factory()->create([
            'user_id' => $userB->id,
            'status' => 1,
        ]);

        //Act
        $response = $this->actingAs($userA)->delete(route('orders.cancel', $orderB));

        //Assert
        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('error', 'この注文を削除する権限がありません。');
        $this->assertDatabaseHas('orders', [
            'id' => $orderB->id,
            'status' => 1,
        ]);
    }
}
