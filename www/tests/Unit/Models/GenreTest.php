<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenreTest extends TestCase
{
    private $genre;

    protected function setUp(): void
    {
        parent::setUp();

        $this->genre = new Genre();
    }

    public function test_FillableAttributes()
    {

        $fillable = ['name', 'is_active'];

        $this->assertEquals(
            $fillable,
            $this->genre->getFillable()
        );
    }

    public function test_IfUseTraits()
    {
        $traits = [
            HasFactory::class,
            SoftDeletes::class,
            Uuid::class
        ];

        $genreTraits = array_keys(class_uses(Genre::class));

        $this->assertEquals(
            $traits,
            $genreTraits
        );
    }

    public function test_DatesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        $genreDates = $this->genre->getDates();

        foreach($dates as $date) {
            $this->assertContains($date, $genreDates);
        }

        $this->assertCount(count($dates), $genreDates);
    }

    public function test_Casts()
    {
        $casts = [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime'
        ];

        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function test_Incrementing()
    {
        $this->assertFalse($this->genre->getIncrementing());
    }
}
