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

    public function test_create() {
        $category = Category::create(
            ['name' => 'Test']
        );

        $category->refresh();

        $this->assertEquals("Test", $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
    }
}
