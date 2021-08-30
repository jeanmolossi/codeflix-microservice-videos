<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Video;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Storage;

class VideoSeeder extends Seeder {

    private $allGenres;
    private $relations = [
        'genres_id' => [],
        'categories_id' => []
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $dir = Storage::getDriver()
            ->getAdapter()
            ->getPathPrefix();

        File::deleteDirectory($dir, true);

        $self = $this;
        $this->allGenres = Genre::all();

        Model::reguard();
        Video::factory(100)
            ->make()
            ->each(function (Video $video) use ($self) {
                $self->fetchRelations();

                Video::create(
                    array_merge(
                        $video->toArray(),
                        [
                            'thumb_file' => $self->getImageFile(),
                            'banner_file' => $self->getImageFile(),
                            'trailer_file' => $self->getVideoFile(),
                            'video_file' => $self->getVideoFile(),
                        ],
                        $this->relations
                    )
                );
            });

        Model::unguard();
    }

    public function fetchRelations() {
        $subGenres = $this->allGenres
            ->random(5)
            ->load('categories');

        $categoriesId = [];

        foreach ($subGenres as $genre) {
            array_push($categoriesId, ...$genre->categories->pluck('id')->toArray());
        }

        $categoriesId = array_unique($categoriesId);
        $genresId = $subGenres->pluck('id')->toArray();

        $this->relations['categories_id'] = $categoriesId;
        $this->relations['genres_id'] = $genresId;
    }

    public function getImageFile(): UploadedFile {
        return new UploadedFile(
            storage_path('faker/thumbs/Laravel Framework.png'),
            'Laravel Framework.png'
        );
    }

    public function getVideoFile(): UploadedFile {
        return new UploadedFile(
            storage_path('faker/videos/Meditating in the living room.mp4'),
            'Meditating in the living room.mp4'
        );
    }
}
