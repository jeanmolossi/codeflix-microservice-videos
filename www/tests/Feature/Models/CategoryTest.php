<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase {
    use DatabaseMigrations;

    public function test_List() {
        Category::factory(1)->create();

        $categories = Category::all();
        $this->assertCount(1, $categories);

        $categoryKeys = array_keys($categories->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKeys
        );
    }

    public function test_Create() {
        $category = Category::create(
            ['name' => 'Test']
        );

        $category->refresh();

        $this->assertEquals("Test", $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            "name" => "Teste",
            "description" => "Descricao de teste",
            "is_active" => false
        ]);

        $category->refresh();

        $this->assertEquals("Teste", $category->name);
        $this->assertEquals("Descricao de teste",$category->description);
        $this->assertFalse($category->is_active);

        $category = Category::create([
            "name" => "Teste",
            "description" => null
        ]);

        $this->assertNull($category->description);

    }


    public function test_Update() {
        /** @var Category $category */
        $category = Category::factory(1)->create()->first();

        $data = [
            "name" => "edited_name",
            "description" => "edited_description",
            "is_active" => false
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }
}
