<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 管理者はカテゴリー一覧を取得できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->count(3)->create();

        // Act
        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    /** @test */
    public function 管理者はカテゴリー詳細を取得できる(): void
    {
        $this->withoutExceptionHandling();
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($user)->get(route('admin.categories.show', $category));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('category');
    }

    /** @test */
    public function 管理者はカテゴリー作成画面を表示できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);

        // Act
        $response = $this->actingAs($user)->get(route('admin.categories.create'));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function 管理者はカテゴリーを作成できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);

        // Act
        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => 'テストカテゴリー',
        ]);

        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'テストカテゴリー',
        ]);
    }

    /** @test */
    public function カテゴリー名が空だとバリデーションエラーになる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);

        // Act
        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function カテゴリー名は255文字まで入力できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);

        // Act
        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => str_repeat('あ', 255),
        ]);

        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => str_repeat('あ', 255),
        ]);
    }

    /** @test */
    public function カテゴリー名が256文字以上だとバリデーションエラーになる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);

        // Act
        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => str_repeat('あ', 256),
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function 管理者はカテゴリー編集画面を表示できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($user)->get(route('admin.categories.edit', $category));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('category');
    }

    /** @test */
    public function 管理者はカテゴリーを更新できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($user)->put(route('admin.categories.update', $category), [
            'name' => '更新後のカテゴリー名',
        ]);

        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => '更新後のカテゴリー名',
        ]);
    }

    /** @test */
    public function 管理者はカテゴリーを削除できる(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $category));

        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function 商品が紐づいているカテゴリーは削除できない(): void
    {
        // Arrange
        $user = User::factory()->create(['role' => 'admin']);
        // 商品が紐づいているカテゴリーを作成する
        $category = Category::factory()->has(Product::factory())->create();

        // Act
        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $category));

        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]); // 削除されていない
    }
}
