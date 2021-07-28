<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CastMemberTest extends TestCase {

    private $castMember;

    protected function setUp(): void {
        parent::setUp();

        $this->castMember = new CastMember();
    }

    public function test_FillableAttributes() {
        $fillable = ['name', 'type'];

        $this->assertEquals(
            $fillable,
            $this->castMember->getFillable()
        );
    }

    public function test_IfUseTraits() {
        $traits = [
            HasFactory::class,
            SoftDeletes::class,
            Uuid::class
        ];

        $castMemberTraits = array_keys(class_uses(CastMember::class));

        $this->assertEquals(
            $traits,
            $castMemberTraits
        );
    }

    public function test_DatesAttributes() {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        $castMemberDates = $this->castMember->getDates();

        foreach ($dates as $date) {
            $this->assertContains($date, $castMemberDates);
        }

        $this->assertCount(count($dates), $castMemberDates);
    }

    public function test_Casts() {
        $casts = [
            'id' => 'string',
            'type' => 'integer',
            'deleted_at' => 'datetime'
        ];

        $this->assertEquals($casts, $this->castMember->getCasts());
    }

    public function test_Incrementing() {
        $this->assertFalse($this->castMember->getIncrementing());
    }
}
