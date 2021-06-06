<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Lang;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_Index() {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function test_Show() {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function test_InvalidationData() {
        $response = $this->json(
            "POST",
            route('categories.store'),
            []
        );

        $this->assertInvalidationRequired($response);

        $response = $this->json(
            "POST",
            route('categories.store'),
            [
                "name" => str_repeat("a",256),
                "is_active" => "a"
            ]
        );

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        /** @section UPDATE */
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json(
            "PUT",
            route('categories.update', ['category' => $category->id]),
            []
        );

        $this->assertInvalidationRequired($response);

        $response = $this->json(
            "PUT",
            route('categories.update', ['category' => $category->id]),
            [
                "name" => str_repeat("a",256),
                "is_active" => "a"
            ]
        );

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    protected function assertInvalidationRequired(TestResponse $response) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["name"])
            ->assertJsonMissingValidationErrors(["is_active"])
            ->assertJsonFragment([
                Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    protected function assertInvalidationMax(TestResponse $response) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["name"])
            ->assertJsonFragment([
                Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    protected function assertInvalidationBoolean(TestResponse $response) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["is_active"])
            ->assertJsonFragment([
                Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }

    public function test_Store() {
        $response = $this->json(
            "POST",
            route("categories.store"),
            ["name" => "Test"]
        );

        $id = $response->json('id');

        $category = Category::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        /** @section TEST WITH description AND is_active */
        $response = $this->json(
            "POST",
            route("categories.store"),
            [
                "name" => "Test",
                "description" => "Now has description",
                "is_active" => false
            ]
        );

        $response
            ->assertJsonFragment([
                'description' => 'Now has description',
                'is_active' => false
            ]);
    }

    public function test_Update() {
        $category = Category::factory()->create([
            'description' => 'Has description',
            'is_active' => false
        ]);

        $response = $this->json(
            "PUT",
            route("categories.update", ['category' => $category->id]),
            [
                "name" => "Test",
                "description" => 'Edited description',
                "is_active" => true
            ]
        );

        $id = $response->json('id');

        $category = Category::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'Edited description',
                'is_active' => true
            ]);
    }
}
