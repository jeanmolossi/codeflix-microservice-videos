<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase {
    use RefreshDatabase;

    public function test_List() {
        Genre::factory(1)->create();

        $categories = Genre::all();
        $this->assertCount(1, $categories);

        $genreKeys = array_keys($categories->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKeys
        );
    }

    public function test_Create() {
        $genre = Genre::create(
            ['name' => 'Test']
        );

        $genre->refresh();

        $this->assertEquals("Test", $genre->name);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create([
            "name" => "Teste",
            "is_active" => false
        ]);

        $genre->refresh();

        $this->assertEquals("Teste", $genre->name);
        $this->assertFalse($genre->is_active);


    }


    public function test_Update() {
        /** @var Genre $genre */
        $genre = Genre::factory(1)->create()->first();

        $data = [
            "name" => "edited_name",
            "is_active" => false
        ];

        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function test_Delete() {
        /** @var Genre genre */
        $genre = Genre::factory()->create();

        $genre->delete();

        $this->assertNull(Genre::find($genre->id));

        $genre->restore();

        $this->assertNotNull(Genre::find($genre->id));
    }
}
