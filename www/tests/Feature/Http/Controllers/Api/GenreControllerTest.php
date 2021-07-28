<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Lang;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase {
    use RefreshDatabase, TestValidations, TestSaves;

    private $genre;

    protected function setUp(): void {
        parent::setUp();

        $this->genre = Genre::factory()->create();
    }

    public function test_Index() {
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function test_Show() {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->genre->toArray());
    }

    public function test_InvalidationData() {
        $data = ['name' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = ['name' => str_repeat('a', 256)];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

        $data = ['is_active' => 'a'];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function test_Store() {
        $data = ["name" => "Test"];

        $this->assertStore($data,
            $data + ['is_active' => true, 'deleted_at' => null],
        );

        $data = [
            "name" => "Test",
            "is_active" => false
        ];

        $this->assertStore($data,
            $data + ['is_active' => false, 'deleted_at' => null],
        );
    }

    public function test_Update() {
        $this->genre = Genre::factory()->create([
            'is_active' => false
        ]);

        $data = [
            "name" => "Test",
            "is_active" => true
        ];

        $this->assertUpdate($data, $data + ['deleted_at' => null]);

        $data = [
            "name" => "Test",
            "is_active" => false
        ];

        $this->assertUpdate($data, array_merge($data, ['is_active' => false]));
    }

    public function test_Destroy() {
        $response = $this->json(
            "DELETE",
            route('genres.destroy', ['genre' => $this->genre->id])
        );

        $response
            ->assertStatus(204);

        $this->assertNull(Genre::all()->find($this->genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($this->genre->id));
    }

    protected function routeStore(): string {
        return route('genres.store');
    }

    protected function routeUpdate(): string {
        return route('genres.update', ['genre' => $this->genre->id]);
    }

    protected function model(): string {
        return Genre::class;
    }
}
