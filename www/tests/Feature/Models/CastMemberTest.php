<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CastMemberTest extends TestCase {
    use RefreshDatabase;

    public function test_List() {
        CastMember::factory(1)->create();

        $castMembers = CastMember::all();
        $this->assertCount(1, $castMembers);

        $castMemberKeys = array_keys($castMembers->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'type', 'created_at', 'updated_at', 'deleted_at'],
            $castMemberKeys
        );
    }

    public function test_Create() {
        $castMember = CastMember::create([
            'name' => 'Test',
            'type' => CastMember::TYPE_ACTOR
        ]);

        $castMember->refresh();

        $this->assertEquals("Test", $castMember->name);
        $this->assertEquals(CastMember::TYPE_ACTOR, $castMember->type);
    }

    public function test_Update() {
        $castMember = CastMember::factory(1)->create()->first();

        $data = [
            'name' => 'edited_name',
            'type' => CastMember::TYPE_ACTOR
        ];

        $castMember->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key});
        }
    }

    public function test_Delete() {
        $castMember = CastMember::factory()->create();

        $castMember->delete();

        $this->assertNull(
            CastMember::all()
                ->find($castMember->id)
        );

        $castMember->restore();

        $this->assertNotNull(
            CastMember::all()
                ->find($castMember->id)
        );
    }
}
