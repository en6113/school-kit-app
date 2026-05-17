<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function ユーザーは商品一覧を取得できる(): void
    {
        // Arrange
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        // Act
        $response = $this->actingAs($user)->get(route('products.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function ユーザーは商品詳細を取得できる(): void
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Act
        $response = $this->actingAs($user)->get(route('products.show', $product));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    public function 業者は商品一覧を取得できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        Product::factory()->count(3)->create();

        // Act
        $response = $this->actingAs($vendor)->get(route('products.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function 業者は商品詳細を取得できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create();

        // Act
        $response = $this->actingAs($vendor)->get(route('products.show', $product));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    public function 業者は商品作成画面を表示できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();

        // Act
        $response = $this->actingAs($vendor, 'vendor')->get(route('vendor.products.create'));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function 業者は商品を作成できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($vendor,'vendor')->post(route('vendor.products.store'), [
            'name' => 'テスト商品',
            'price' => 300,
            'total_stock' => 300,
            'description' => 'テスト商品です',
            'productImages.image-url' => 'https://example.com/images/test.jpg',
            'categories.category_id' => 1,
        ]);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'vendor_id' => $vendor->id,
        ]);
    }

    /** @test */
    public function 商品名が空だとバリデーションエラーになる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($vendor, 'vendor')->post(route('vendor.products.store'), [
            'name' => ' ',
            'price' => 300,
            'total_stock' => 300,
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    public function 価格が空だとバリデーションエラーになる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($vendor, 'vendor')->post(route('vendor.products.store'), [
            'name' => 'テスト商品',
            'price' => '',
            'total_stock' => 300,
        ]);

        // Assert
        $response->assertSessionHasErrors('price');
    }

    public function 在庫数が空だとバリデーションエラーになる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($vendor, 'vendor')->post(route('vendor.products.store'), [
            'name' => 'テスト商品',
            'price' => 300,
            'total_stock' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('total_stock');
    }

    /** @test */
    public function 商品名は255文字まで入力できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();

        // Act
        $response = $this->actingAs($vendor, 'vendor')->post(route('vendor.products.store'), [
            'name' => str_repeat('あ', 255),
            'price' => 300,
            'total_stock' => 300,
        ]);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => str_repeat('あ', 255),
            'vendor_id' => $vendor->id,
        ]);
    }

    /** @test */
    public function 商品名が256文字以上だとバリデーションエラーになる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();

        // Act
        $response = $this->actingAs($vendor, 'vendor')->post(route('vendor.products.store'), [
            'name' => str_repeat('あ', 256),
            'price' => 300,
            'total_stock' => 300,
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function 業者は商品編集画面を表示できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        // Act
        $response = $this->actingAs($vendor, 'vendor')->get(route('vendor.products.edit', $product));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    /** @test */
    public function 業者は自分の商品を更新できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        // Act
        $response = $this->actingAs($vendor, 'vendor')->put(route('vendor.products.update', $product), [
            'name' => '更新後の商品名',
            'price' => 200,
            'total_stock' => 200,
        ]);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => '更新後の商品名',
            'price' => 200,
            'total_stock' =>200,
        ]);
    }

    /** @test */
    public function 業者は自分の商品を削除できる(): void
    {
        // Arrange
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        // Act
        $response = $this->actingAs($vendor, 'vendor')->delete(route('vendor.products.destroy', $product));

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
