<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Resources\CastMemberResource;
use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestResources;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase {
    use DatabaseMigrations, TestValidations, TestSaves, TestResources;

    private $castMember;

    private $serializedFields = [
        'id',
        'name',
        'type',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function test_Index() {
        $response = $this->get(route('cast_members.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->serializedFields
                ],
                'meta' => [],
                'links' => []
            ]);

        $this->assertResource(
            $response,
            CastMemberResource::collection(collect([$this->castMember]))
        );
    }

    public function test_InvalidationData() {
        $data = [
            'name' => '',
            'type' => ''
        ];

        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = ['type' => 's'];

        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');
    }

    public function test_Store() {
        $data = [
            [
                'name' => 'test',
                'type' => CastMember::TYPE_DIRECTOR
            ],
            [
                'name' => 'test',
                'type' => CastMember::TYPE_ACTOR
            ]
        ];

        foreach ($data as $value) {
            $response = $this->assertStore($value, $value + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'data' => $this->serializedFields
            ]);

            $this->assertResource($response, new CastMemberResource(
                CastMember::find($this->getIdFromResponse($response))
            ));
        }
    }

    public function test_Update() {
        CastMember::factory(1)->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);

        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_ACTOR
        ];

        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);

        $this->assertResource($response, new CastMemberResource(
            CastMember::find($this->getIdFromResponse($response))
        ));
    }

    public function test_Show() {
        $response = $this->json(
            'GET',
            route('cast_members.show', ['cast_member' => $this->castMember->id])
        );

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->serializedFields
            ]);

        $this->assertResource($response, new CastMemberResource(
            CastMember::find($this->getIdFromResponse($response))
        ));
    }

    public function test_Destroy() {
        $response = $this->json(
            'DELETE',
            route('cast_members.destroy', ['cast_member' => $this->castMember->id])
        );

        $response->assertStatus(204);

        $this->assertNull(CastMember::all()->find(
            $this->castMember->id
        ));
        $this->assertNotNull(CastMember::withTrashed()->find(
            $this->castMember->id
        ));
    }

    protected function setUp(): void {
        parent::setUp();

        $this->castMember = CastMember::factory()->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);
    }

    protected function model(): string {
        return CastMember::class;
    }

    protected function routeStore(): string {
        return route('cast_members.store');
    }

    protected function routeUpdate(): string {
        return route('cast_members.update', [$this->castMember->id]);
    }
}
