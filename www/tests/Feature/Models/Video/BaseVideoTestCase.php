<?php

namespace Tests\Feature\Models\Video;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class BaseVideoTestCase extends TestCase {
    use RefreshDatabase;

    protected $data;

    protected function setUp(): void {
        parent::setUp();

        $this->data = [
            'title' => 'title_test',
            'description' => 'description_test',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90,
        ];
    }
}
