<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestResources;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase {
    use RefreshDatabase, TestValidations, TestSaves, TestResources;

    private $category;

    private $serializedFields = [
        'id',
        'name',
        'description',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function setUp(): void {
        parent::setUp();

        $this->category = Category::factory()->create();
    }

    public function test_Index() {
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['per_page' => 15]
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->serializedFields
                ],
                'links' => [],
                'meta' => [],
            ]);

        $resource = CategoryResource::collection(collect([$this->category]));

        $this->assertResource($response, $resource);
    }

    public function test_Show() {
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->serializedFields
            ]);

        $id = $this->getIdFromResponse($response);

        $resource = new CategoryResource(Category::all()->find($id));

        $this->assertResource($response, $resource);
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

        $response = $this->assertStore($data,
            $data + ['description' => null, 'is_active' => true, 'deleted_at' => null],
        );
        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);

        $data = [
            "name" => "Test",
            "description" => "Now has description",
            "is_active" => false
        ];

        $response = $this->assertStore($data,
            $data + ['description' => 'Now has description', 'is_active' => false, 'deleted_at' => null],
        );

        $id = $this->getIdFromResponse($response);

        $category = Category::all()->find($id);
        $resource = new CategoryResource($category);

        $this->assertResource($response, $resource);
    }

    public function test_Update() {
        $this->category = Category::factory()->create([
            'description' => 'Has description',
            'is_active' => false
        ]);

        $data = [
            "name" => "Test",
            "description" => 'Edited description',
            "is_active" => true
        ];

        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);

        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);

        $id = $this->getIdFromResponse($response);

        $category = Category::all()->find($id);
        $resource = new CategoryResource($category);

        $this->assertResource($response, $resource);

        $data = [
            "name" => "Test",
            "description" => '',
            "is_active" => true
        ];

        $this->assertUpdate($data, array_merge($data, ['description' => null]));

        $data['description'] = 'test';

        $this->assertUpdate($data, array_merge($data, ['description' => 'test']));
    }

    public function test_Destroy() {
        $response = $this->json(
            "DELETE",
            route('categories.destroy', ['category' => $this->category->id])
        );

        $response
            ->assertStatus(204);

        $this->assertNull(Category::all()->find($this->category->id));
        $this->assertNotNull(Category::withTrashed()->find($this->category->id));
    }

    protected function routeStore(): string {
        return route('categories.store');
    }

    protected function routeUpdate(): string {
        return route('categories.update', ['category' => $this->category->id]);
    }

    protected function model(): string {
        return Category::class;
    }
}
