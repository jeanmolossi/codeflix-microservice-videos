<?php

namespace Tests\Unit;

use App\Models\Category;
use PHPUnit\Framework\TestCase;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTest extends TestCase
{

    public function test_fillableAttributes()
    {
        $fillable = ['name', 'description', 'is_active'];
        $category = new Category();

        $this->assertEquals(
            $fillable,
            $category->getFillable()
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
        $category = new Category();
        $categoryDates = $category->getDates();

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
        $category = new Category();

        $this->assertEquals($casts, $category->getCasts());
    }

    public function test_incrementing()
    {
        $category = new Category();

        $this->assertFalse($category->getIncrementing());
    }
}
