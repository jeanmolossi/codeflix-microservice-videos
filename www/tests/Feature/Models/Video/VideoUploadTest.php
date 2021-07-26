<?php

namespace Tests\Feature\Models\Video;

use App\Models\Video;
use Event;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\Exceptions\TestException;

class VideoUploadTest extends BaseVideoTestCase {

    public function test_CreateWithFiles() {
        Storage::fake();
        $video = Video::create(
            $this->data + [
                'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                'video_file' => UploadedFile::fake()->image('video.mp4'),
            ]
        );
        Storage::assertExists("{$video->id}/{$video->thumb_file}");
        Storage::assertExists("{$video->id}/{$video->video_file}");
    }

    public function test_CreateIfRollbackFiles() {
        Storage::fake();
        Event::listen(TransactionCommitted::class, function () {
            throw new TestException();
        });

        $hasError = false;

        try {
            Video::create(
                $this->data + [
                    'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                    'video_file' => UploadedFile::fake()->create('video.mp4'),
                ]
            );
        } catch (TestException $e) {
            $this->assertCount(0, Storage::allFiles());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

}
