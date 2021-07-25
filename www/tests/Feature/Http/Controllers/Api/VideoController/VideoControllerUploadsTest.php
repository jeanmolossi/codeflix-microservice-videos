<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\Traits\TestUploads;
use Tests\Traits\TestValidations;

class VideoControllerUploadsTest extends BaseVideoControllerTestCase {
    use TestValidations, TestUploads;

    public function test_InvalidationVideoField() {
        $this->assertInvalidationFile(
            'video_file',
            'mp4',
            12,
            'mimetypes', ['values' => 'video/mp4']
        );
    }

    public function test_StoreWithFiles() {
        Storage::fake();
        $files = $this->getFiles();

        $category = Category::factory()->create();
        $genre = Genre::factory()->create();
        $genre->categories()->sync($category->id);

        $response = $this->json(
            'POST',
            $this->routeStore(),
            $this->sendData +
            [
                'categories_id' => [$category->id],
                'genres_id' => [$genre->id]
            ] + $files
        );

        $response->assertStatus(201);
        $id = $response->json('id');
        foreach ($files as $file) {
            Storage::assertExists("{$id}/{$file->hashName()}");
        }
    }

    public function test_UpdateWithFiles() {
        Storage::fake();
        $files = $this->getFiles();

        $category = Category::factory()->create();
        $genre = Genre::factory()->create();
        $genre->categories()->sync($category->id);

        $response = $this->json(
            'PUT',
            $this->routeUpdate(),
            $this->sendData +
            [
                'categories_id' => [$category->id],
                'genres_id' => [$genre->id]
            ] + $files
        );

        $response->assertStatus(200);
        $id = $response->json('id');
        foreach ($files as $file) {
            Storage::assertExists("{$id}/{$file->hashName()}");
        }
    }

    protected function getFiles(): array {
        return [
            'video_file' => UploadedFile::fake()->create('video_file.mp4')
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
