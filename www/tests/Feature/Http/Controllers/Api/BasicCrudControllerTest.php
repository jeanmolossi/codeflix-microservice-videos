<?php

namespace Tests\Feature\Http\Controllers\Api;


use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Mockery;
use ReflectionClass;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Illuminate\Http\Request;

class BasicCrudControllerTest extends TestCase {

    private $controller;

    protected function setUp(): void {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function test_Index() {
        /** @var CategoryStub $category */
        $category = CategoryStub::create([
            'name' => 'test_name',
            'description' => 'test_description'
        ]);

        $result = $this->controller->index()->toArray();

        $this->assertEquals([$category->toArray()], $result);
    }

    public function test_InvalidationDataInStore() {
        $this->expectException(ValidationException::class);

        $request = Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['name' => null]);

        $this->controller->store($request);
    }

    public function test_Store() {
        $request = Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn([
                'name' => 'test_name',
                'description' => 'test_description'
            ]);

        $obj = $this->controller->store($request);

        $this->assertEquals(
            CategoryStub::all()->find(1)->toArray(),
            $obj->toArray()
        );
    }

    public function test_FindOrFailFetchModel() {
        /** @var CategoryStub $category */
        $category = CategoryStub::create([
            'name' => 'test_name',
            'description' => 'test_description'
        ]);

        $reflectionClass = new ReflectionClass(BasicCrudController::class);

        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);

        $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function test_FindOrFailThrowExceptionWhenIdInvalid() {
        $this->expectException(ModelNotFoundException::class);

        $reflectionClass = new ReflectionClass(BasicCrudController::class);

        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [0]);

        $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function test_Show() {
        /** @var CategoryStub $category */
        $category = CategoryStub::create([
            'name' => 'test_name',
            'description' => 'test_description'
        ]);

        $result = $this->controller->show($category->id);

        $this->assertEquals(
            $result->toArray(),
            CategoryStub::all()->find(1)->toArray()
        );
    }

    public function test_Update() {
        /** @var CategoryStub $category */
        $category = CategoryStub::create([
            'name' => 'test_name',
            'description' => 'test_description'
        ]);

        $request = Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn([
                'name' => 'test_name',
                'description' => 'test_description'
            ]);

        $result = $this->controller->update($request, $category->id);

        $this->assertEquals(
            $result->toArray(),
            CategoryStub::all()->find(1)->toArray()
        );
    }

    public function test_Destroy() {
        /** @var CategoryStub $category */
        $category = CategoryStub::create([
            'name' => 'test_name',
            'description' => 'test_description'
        ]);

        $response = $this->controller->destroy($category->id);

        $this->
            createTestResponse($response)
            ->assertStatus(204);

        $this->assertCount(0, CategoryStub::all());
    }
}
