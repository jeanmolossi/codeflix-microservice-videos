<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\GenreController;
use App\Http\Resources\GenreResource;
use App\Models\Category;
use App\Models\Genre;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\Exceptions\TestException;
use Tests\TestCase;
use Tests\Traits\TestResources;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase {
    use RefreshDatabase, TestValidations, TestSaves, TestResources;

    private $genre;

    private $sendData;

    private $fieldsSerialized = [
        'id',
        'name',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'categories' => [
            '*' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ]
    ];

    protected function setUp(): void {
        parent::setUp();

        $this->genre = Genre::factory()->create();

        $this->sendData = [
            'name' => 'test_name',
            'is_active' => true
        ];
    }

    public function test_Index() {
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->fieldsSerialized
                ],
                'meta' => [],
                'links' => [],
            ]);

        $this->assertResource(
            $response,
            GenreResource::collection(collect([$this->genre]))
        );
    }

    public function test_Show() {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->fieldsSerialized
            ])
            ->assertJsonFragment($this->genre->toArray());

        $this->assertResource(
            $response,
            new GenreResource($this->genre)
        );
    }

    public function test_InvalidationData() {
        $data = ['name' => '', 'categories_id' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = ['name' => str_repeat('a', 256)];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

        $data = ['is_active' => 'a'];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');

        $data = ['categories_id' => 'a'];
        $this->assertInvalidationInStoreAction($data, 'array');
        $this->assertInvalidationInUpdateAction($data, 'array');

        $data = ['categories_id' => [0]];
        $this->assertInvalidationInStoreAction($data, 'exists');
        $this->assertInvalidationInUpdateAction($data, 'exists');
    }

    public function test_Save() {
        $categoryId = Category::factory()->create()->id;

        $data = [
            [
                'send_data' => [
                    'name' => 'test',
                    'categories_id' => [$categoryId]
                ],
                'test_data' => [
                    'name' => 'test',
                    'is_active' => true
                ]
            ],
            [
                'send_data' => [
                    'name' => 'test',
                    'is_active' => false,
                    'categories_id' => [$categoryId]
                ],
                'test_data' => [
                    'name' => 'test',
                    'is_active' => false
                ]
            ]
        ];

        foreach ($data as $test) {
            $response = $this->assertStore($test['send_data'], $test['test_data']);
            $response->assertJsonStructure([
                'data' => $this->fieldsSerialized
            ]);

            $this->assertResource($response, new GenreResource(
                Genre::find($this->getIdFromResponse($response))
            ));

            $response = $this->assertUpdate($test['send_data'], $test['test_data']);
            $response->assertJsonStructure([
                'data' => $this->fieldsSerialized
            ]);

            $this->assertResource($response, new GenreResource(
                Genre::find($this->getIdFromResponse($response))
            ));
        }
    }

    protected function assertHasCategory(string $genreId, string $categoryId) {
        $this->assertDatabaseHas('category_genre', [
            'genre_id' => $genreId,
            'category_id' => $categoryId
        ]);
    }

    public function testSyncCategories() {
        $categoriesId = Category::factory(3)->create()->pluck('id')->toArray();

        $sendData = [
            'name' => 'test',
            'categories_id' => [$categoriesId[0]]
        ];

        $response = $this->json('POST', $this->routeStore(), $sendData);

        $this->assertDatabaseHas('category_genre', [
            'category_id' => $categoriesId[0],
            'genre_id' => $this->getIdFromResponse($response)
        ]);

        $sendData = [
            'name' => 'test',
            'categories_id' => [$categoriesId[1], $categoriesId[2]]
        ];

        $genreId = $this->getIdFromResponse($response);

        $response = $this->json(
            'PUT',
            route('genres.update', ['genre' => $genreId]),
            $sendData
        );

        $this->assertDatabaseMissing('category_genre', [
            'category_id' => $categoriesId[0],
            'genre_id' => $genreId
        ]);
        $this->assertDatabaseHas('category_genre', [
            'category_id' => $categoriesId[1],
            'genre_id' => $genreId
        ]);
        $this->assertDatabaseHas('category_genre', [
            'category_id' => $categoriesId[2],
            'genre_id' => $genreId
        ]);
    }

    public function test_RollbackStore() {
        $controller = Mockery::mock(GenreController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller
            ->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn($this->sendData);

        $controller
            ->shouldReceive('rulesStore')
            ->withAnyArgs()
            ->andReturn([]);

        $controller
            ->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());

        $request = Mockery::mock(Request::class);

        $hasError = false;
        try {
            $controller->store($request);
        } catch (TestException $e) {
            $this->assertCount(1, Genre::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function test_RollbackUpdate() {
        $controller = Mockery::mock(GenreController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller
            ->shouldReceive('findOrFail')
            ->withAnyArgs()
            ->andReturn($this->genre);

        $controller
            ->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn([
                'name' => 'updated_name'
            ]);

        $controller
            ->shouldReceive('rulesUpdate')
            ->withAnyArgs()
            ->andReturn([]);

        $controller
            ->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());

        $request = Mockery::mock(Request::class);

        $hasError = false;
        try {
            $controller->update($request, 'fake_id');
        } catch (TestException $e) {
            $this->assertCount(1, Genre::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
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
