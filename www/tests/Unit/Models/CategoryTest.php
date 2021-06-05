<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTest extends TestCase
{
    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    public function test_fillableAttributes()
    {

        $fillable = ['name', 'description', 'is_active'];

        $this->assertEquals(
            $fillable,
            $this->category->getFillable()
        );
    }

    public function test_ifUseTraits()
    {
        $traits = [
            HasFactory::class,
            SoftDeletes::class,
            Uuid::class
        ];

        $categoryTraits = array_keys(class_uses(Category::class));

        $this->assertEquals(
            $traits,
            $categoryTraits
        );
    }

    public function test_datesAttributes()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        $categoryDates = $this->category->getDates();

        foreach($dates as $date) {
            $this->assertContains($date, $categoryDates);
        }

        $this->assertCount(count($dates), $categoryDates);
    }

    public function test_casts()
    {
        $casts = [
            'id' => 'string',
            'deleted_at' => 'datetime'
        ];

        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function test_incrementing()
    {
        $this->assertFalse($this->category->getIncrementing());
    }
}
