<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Lang;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_Index() {
        $genre = Genre::factory()->create();

        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function test_Show() {
        $genre = Genre::factory()->create();

        $response = $this->get(route('genres.show', ['genre' => $genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function test_InvalidationData() {
        $response = $this->json(
            "POST",
            route('genres.store'),
            []
        );

        $this->assertInvalidationRequired($response);

        $response = $this->json(
            "POST",
            route('genres.store'),
            [
                "name" => str_repeat("a",256),
                "is_active" => "a"
            ]
        );

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        /** @section UPDATE */
        /** @var Genre $genre */
        $genre = Genre::factory()->create();

        $response = $this->json(
            "PUT",
            route('genres.update', ['genre' => $genre->id]),
            []
        );

        $this->assertInvalidationRequired($response);

        $response = $this->json(
            "PUT",
            route('genres.update', ['genre' => $genre->id]),
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
            route("genres.store"),
            ["name" => "Test"]
        );

        $id = $response->json('id');

        $genre = Genre::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($genre->toArray());

        $this->assertTrue($response->json('is_active'));

        /** @section TEST WITH description AND is_active */
        $response = $this->json(
            "POST",
            route("genres.store"),
            [
                "name" => "Test",
                "is_active" => false
            ]
        );

        $response
            ->assertJsonFragment([
                'is_active' => false
            ]);
    }

    public function test_Update() {
        $genre = Genre::factory()->create([
            'is_active' => false
        ]);

        $response = $this->json(
            "PUT",
            route("genres.update", ['genre' => $genre->id]),
            [
                "name" => "Test",
                "is_active" => true
            ]
        );

        $id = $response->json('id');

        $genre = Genre::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'is_active' => true
            ]);
    }

    public function test_Destroy() {
        /** @var Genre $genre */
        $genre = Genre::factory()->create();

        $response = $this->json(
            "DELETE",
            route('genres.destroy', ['genre' => $genre->id])
        );

        $response
            ->assertStatus(204);

        $this->assertNull(Genre::find($genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($genre->id));
    }
}
