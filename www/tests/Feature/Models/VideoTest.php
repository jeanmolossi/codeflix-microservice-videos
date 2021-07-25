<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoTest extends TestCase {
    use RefreshDatabase;

    public function test_RollbackCreate() {
        $hasError = false;
        try {
            Video::create([
                'title' => 'title_test',
                'description' => 'description_test',
                'year_launched' => 2010,
                'rating' => Video::RATING_LIST[0],
                'duration' => 90,
                'categories_id' => [0, 1, 2]
            ]);
        } catch (QueryException $e) {
            $this->assertCount(0, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function test_RollbackUpdate() {
        $video = Video::factory()->create();

        $oldTitle = $video->title;

        $hasError = false;
        try {
            $video->update([
                'title' => 'title_test',
                'description' => 'description_test',
                'year_launched' => 2010,
                'rating' => Video::RATING_LIST[0],
                'duration' => 90,
                'categories_id' => [0, 1, 2]
            ]);
        } catch (QueryException $e) {
            $this->assertDatabaseHas('videos', [
               'title' => $oldTitle
            ]);
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }
}
