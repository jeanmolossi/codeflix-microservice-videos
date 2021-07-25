<?php

namespace Tests\Unit\Models\Traits;

use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;
use Tests\Traits\UploadFilesStub;

class UploadFilesTest extends TestCase {
    private $object;

    protected function setUp(): void {
        parent::setUp();

        $this->object = new UploadFilesStub;
    }

    public function test_UploadFile() {
        Storage::fake();
        $file = UploadedFile::fake()->create('video.mp4');
        $this->object->uploadFile($file);
        Storage::assertExists("1/{$file->hashName()}");
    }

    public function test_UploadFiles() {
        Storage::fake();
        $file_1 = UploadedFile::fake()->create('video.mp4');
        $file_2 = UploadedFile::fake()->create('video-2.mp4');
        $this->object->uploadFiles([$file_1, $file_2]);
        Storage::assertExists("1/{$file_1->hashName()}");
        Storage::assertExists("1/{$file_2->hashName()}");
    }

    public function test_DeleteFile() {
        Storage::fake();
        $file = UploadedFile::fake()->create("video.mp4");
        $filename = $file->hashName();

        $this->object->uploadFile($file);
        $this->object->deleteFile($filename);

        Storage::assertMissing("1/{$filename}");

        $file = UploadedFile::fake()->create("video.mp4");

        $this->object->uploadFile($file);
        $this->object->deleteFile($file);

        Storage::assertMissing("1/{$file->hashName()}");
    }

    public function test_DeleteFiles() {
        Storage::fake();
        $file_1 = UploadedFile::fake()->create('video.mp4');
        $file_2 = UploadedFile::fake()->create('video-2.mp4');
        $this->object->uploadFiles([$file_1, $file_2]);
        $this->object->deleteFiles([$file_1->hashName(), $file_2]);
        Storage::assertMissing("1/{$file_1->hashName()}");
        Storage::assertMissing("1/{$file_2->hashName()}");
    }

    public function test_ExtractFilesWithFileNames() {
        $attributes = [];

        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(0, $attributes);
        $this->assertCount(0, $files);

        $attributes = ['file_1' => 'test'];

        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(1, $attributes);
        $this->assertEquals(['file_1' => 'test'], $attributes);
        $this->assertCount(0, $files);

        $attributes = ['file_1' => 'test', 'file_2' => 'test'];

        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(2, $attributes);
        $this->assertEquals(['file_1' => 'test', 'file_2' => 'test'], $attributes);
        $this->assertCount(0, $files);
    }

    public function test_ExtractFilesUploadedFileInstance() {
        $file_1 = UploadedFile::fake()->create('video_1.mp4');

        $attributes = ['file_1' => $file_1, 'file_2' => 'test'];

        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(2, $attributes);
        $this->assertEquals(['file_1' => $file_1->hashName(), 'file_2' => 'test'], $attributes);
        $this->assertCount(1, $files);
        $this->assertEquals([$file_1], $files);

        $file_2 = UploadedFile::fake()->create('video_2.mp4');

        $attributes = ['file_1' => $file_1, 'file_2' => $file_2,'other' => 'test'];

        $files = UploadFilesStub::extractFiles($attributes);

        $this->assertCount(3, $attributes);
        $this->assertEquals(
            ['file_1' => $file_1->hashName(), 'file_2' => $file_2->hashName(),'other' => 'test'],
            $attributes
        );
        $this->assertCount(2, $files);
        $this->assertEquals([$file_1, $file_2], $files);
    }

}
