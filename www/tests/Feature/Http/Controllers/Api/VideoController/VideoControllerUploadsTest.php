<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Video;
use Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Storage;
use Tests\Traits\TestUploads;
use Tests\Traits\TestValidations;

class VideoControllerUploadsTest extends BaseVideoControllerTestCase {
    use TestValidations, TestUploads;

    public function test_InvalidationThumbField() {
        $this->assertInvalidationFile(
            'thumb_file',
            'jpg',
            Video::THUMB_FILE_MAX_SIZE,
            'image'
        );
    }

    public function test_InvalidationBannerField() {
        $this->assertInvalidationFile(
            'banner_file',
            'jpg',
            Video::BANNER_FILE_MAX_SIZE,
            'image'
        );
    }

    public function test_InvalidationTrailerField() {
        $this->assertInvalidationFile(
            'trailer_file',
            'mp4',
            Video::TRAILER_FILE_MAX_SIZE,
            'mimetypes', ['values' => 'video/mp4']
        );
    }


    public function test_InvalidationVideoField() {
        $this->assertInvalidationFile(
            'video_file',
            'mp4',
            Video::VIDEO_FILE_MAX_SIZE,
            'mimetypes', ['values' => 'video/mp4']
        );
    }

    public function test_StoreWithFiles() {
        Storage::fake();
        $files = $this->getFiles();

        $response = $this->json(
            'POST',
            $this->routeStore(),
            $this->sendData + $files
        );

        $response->assertStatus(201);
        $this->assertFilesOnPersist($response, $files);
    }

    public function test_UpdateWithFiles() {
        Storage::fake();
        $files = $this->getFiles();

        $response = $this->json(
            'PUT',
            $this->routeUpdate(),
            $this->sendData + $files
        );

        $response->assertStatus(200);
        $this->assertFilesOnPersist($response, $files);

        $newFiles = [
            'thumb_file' => UploadedFile::fake()->create('thumb_file.jpg'),
            'video_file' => UploadedFile::fake()->create('video_file.mp4')
        ];

        $response = $this->json(
            'PUT',
            $this->routeUpdate(),
            $this->sendData + $newFiles
        );
        $response->assertStatus(200);
        $this->assertFilesOnPersist(
            $response,
            Arr::except($files, ['thumb_file', 'video_file']) + $newFiles
        );

        $id = $response->json('id') ?? $response->json('data.id');

        /** @var Video $video */
        $video = Video::all()->find($id);

        Storage::assertMissing(
            $video->relativeFilePath($files['thumb_file']->hashName())
        );
        Storage::assertMissing(
            $video->relativeFilePath($files['video_file']->hashName())
        );
    }

    protected function assertFilesOnPersist(TestResponse $response, $files) {
        $id = $response->json('id') ?? $response->json('data.id');

        $video = Video::all()->find($id);

        $this->assertFilesExistsInStorage($video, $files);
    }

    protected function getFiles(): array {
        return [
            'video_file' => UploadedFile::fake()->create('video_file.mp4'),
            'trailer_file' => UploadedFile::fake()->create('trailer_file.mp4'),
            'thumb_file' => UploadedFile::fake()->create('thumb_file.jpg'),
            'banner_file' => UploadedFile::fake()->create('banner_file.jpg'),
        ];
    }

    protected function routeStore(): string {
        return route('videos.store');
    }

    protected function routeUpdate(): string {
        return route('videos.update', ['video' => $this->video->id]);
    }

    protected function model(): string {
        return Video::class;
    }
}
