<?php

namespace Tests\Feature\Models\Video;


use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Database\QueryException;

class VideoCrudTest extends BaseVideoTestCase {

    public function test_List() {
        Video::factory()->create();

        $videos = Video::all();

        $this->assertCount(1, $videos);

        $videoKeys = array_keys($videos->first()->getAttributes());

        $this->assertEqualsCanonicalizing([
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'video_file',
            'created_at',
            'updated_at',
            'deleted_at',
        ], $videoKeys);
    }

    public function test_CreateWithRelations() {
        $generate = $this->generateCategoryAndGenre();
        $category = $generate['category'];
        $genre = $generate['genre'];

        $video = Video::create($this->data + [
                'categories_id' => [$category->id],
                'genres_id' => [$genre->id],
            ]);

        $this->assertHasCategory($video->id, $category->id);
        $this->assertHasGenre($video->id, $genre->id);
    }

    public function test_CreateWithBasicFields() {
        $video = Video::create($this->data);
        $video->refresh();

        $this->assertEquals(36, strlen($video->id));
        $this->assertFalse($video->opened);
        $this->assertDatabaseHas('videos', $this->data + ['opened' => false]);

        $video = Video::create($this->data + ['opened' => true]);
        $this->assertTrue($video->opened);
        $this->assertDatabaseHas('videos', ['opened' => true]);
    }

    public function test_RollbackCreate() {
        $hasError = false;
        try {
            Video::create([
                'categories_id' => [0, 1, 2]
            ]);
        } catch (QueryException $e) {
            $this->assertCount(0, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function test_UpdateWithBasicFields() {
        $video = Video::factory()->create(
            ['opened' => false]
        );
        $video->update($this->data);

        $this->assertFalse($video->opened);
        $this->assertDatabaseHas('videos', $this->data + ['opened' => false]);

        $video = Video::factory()->create(
            ['opened' => false]
        );
        $video->update($this->data + ['opened' => true]);

        $this->assertTrue($video->opened);
        $this->assertDatabaseHas('videos', $this->data + ['opened' => true]);
    }

    public function test_UpdateWithRelations() {
        $category = Category::factory()->create();
        $genre = Genre::factory()->create();
        $video = Video::factory()->create();

        $video->update($this->data + [
                'categories_id' => [$category->id],
                'genres_id' => [$genre->id]
            ]
        );

        $this->assertHasCategory($video->id, $category->id);
        $this->assertHasGenre($video->id, $genre->id);
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

    protected function assertHasCategory(string $videoId, string $categoryId) {
        $this->assertDatabaseHas('category_video', [
            'video_id' => $videoId,
            'category_id' => $categoryId
        ]);
    }

    protected function assertHasGenre(string $videoId, string $genreId) {
        $this->assertDatabaseHas('genre_video', [
            'video_id' => $videoId,
            'genre_id' => $genreId
        ]);
    }

    public function test_HandleRelations() {
        $video = Video::factory()->create();
        Video::handleRelations($video, []);

        $this->assertCount(0, $video->categories);
        $this->assertCount(0, $video->genres);

        $category = Category::factory()->create();
        Video::handleRelations($video, ['categories_id' => [$category->id]]);
        $video->refresh();

        $this->assertCount(1, $video->categories);

        $genre = Genre::factory()->create();
        Video::handleRelations($video, ['genres_id' => [$genre->id]]);
        $video->refresh();

        $this->assertCount(1, $video->genres);

        $video->categories()->delete();
        $video->genres()->delete();

        Video::handleRelations($video, [
            'categories_id' => [$category->id],
            'genres_id' => [$genre->id]
        ]);
        $video->refresh();

        $this->assertCount(1, $video->categories);
        $this->assertCount(1, $video->genres);
    }

    public function test_SyncCategories() {
        $categoriesId = Category::factory(3)->create()->pluck('id')->toArray();

        $video = Video::factory()->create();
        Video::handleRelations($video, [
            'categories_id' => [$categoriesId[0]]
        ]);

        $this->assertDatabaseHas('category_video', [
            'category_id' => $categoriesId[0],
            'video_id' => $video->id
        ]);

        Video::handleRelations($video, [
            'categories_id' => [$categoriesId[1], $categoriesId[2]]
        ]);

        $this->assertDatabaseMissing('category_video', [
            'category_id' => $categoriesId[0],
            'video_id' => $video->id
        ]);
        $this->assertDatabaseHas('category_video', [
            'category_id' => $categoriesId[1],
            'video_id' => $video->id
        ]);
        $this->assertDatabaseHas('category_video', [
            'category_id' => $categoriesId[2],
            'video_id' => $video->id
        ]);
    }

    public function test_SyncGenres() {
        $genresId = Genre::factory(3)->create()->pluck('id')->toArray();

        $video = Video::factory()->create();
        Video::handleRelations($video, [
            'genres_id' => [$genresId[0]]
        ]);

        $this->assertDatabaseHas('genre_video', [
            'genre_id' => $genresId[0],
            'video_id' => $video->id
        ]);

        Video::handleRelations($video, [
            'genres_id' => [$genresId[1], $genresId[2]]
        ]);

        $this->assertDatabaseMissing('genre_video', [
            'genre_id' => $genresId[0],
            'video_id' => $video->id
        ]);
        $this->assertDatabaseHas('genre_video', [
            'genre_id' => $genresId[1],
            'video_id' => $video->id
        ]);
        $this->assertDatabaseHas('genre_video', [
            'genre_id' => $genresId[2],
            'video_id' => $video->id
        ]);
    }

    public function test_Delete() {
        $video = Video::factory()->create();
        $video->delete();

        $this->assertNull(
            Video::all()->find($video->id)
        );

        $video->restore();

        $this->assertNotNull(
            Video::all()->find($video->id)
        );
    }

    protected function generateCategoryAndGenre(): array {
        $category = Category::factory()->create();
        $genre = Genre::factory()->create();

        return [
            'category' => $category,
            'genre' => $genre
        ];
    }
}
