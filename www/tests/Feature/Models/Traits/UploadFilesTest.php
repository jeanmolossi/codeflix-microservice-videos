<?php

namespace Tests\Feature\Models\Traits;

use Tests\TestCase;
use Tests\Traits\UploadFilesStub;

class UploadFilesTest extends TestCase {
    private $object;

    protected function setUp(): void {
        parent::setUp();
        $this->object = new UploadFilesStub();
    }

    public function test_MakeOldFieldsOnSaving() {
        UploadFilesStub::dropTable();
        UploadFilesStub::makeTable();

        $this->object->fill([
            'name' => 'test',
            'file_1' => 'test1.mp4',
            'file_2' => 'test2.mp4',
        ]);

        $this->object->save();

        $this->assertCount(0, $this->object->oldFiles);

        $this->object->update([
            'name' => 'test_name',
            'file_2' => 'test3.mp4'
        ]);

        $this->assertEqualsCanonicalizing(['test2.mp4'], $this->object->oldFiles);
    }

}
