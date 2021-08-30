<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class BaseVideoControllerTestCase extends TestCase
{
    use RefreshDatabase;

    protected $video;

    protected $sendData;

    protected function setUp(): void {
        parent::setUp();

        $this->video = Video::factory()->create(['opened' => false]);

        $category = Category::factory()->create();

        /** @var Genre $genre */
        $genre = Genre::factory()->create();
        $genre->categories()->sync($category->id);

        $this->sendData = [
            'title' => 'title_test',
            'description' => 'description_test',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90,
            'categories_id' => [$category->id],
            'genres_id' => [$genre->id],
        ];
    }

}
