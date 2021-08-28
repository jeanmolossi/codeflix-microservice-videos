<?php

namespace App\Models;

use App\Models\Traits\UploadFiles;
use App\Models\Traits\Uuid;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Throwable;

class Video extends Model {
    use SoftDeletes, Uuid, HasFactory, UploadFiles;

    const NO_RATING = 'L';
    const RATING_LIST = [self::NO_RATING, '10', '12', '14', '16', '18'];

    const THUMB_FILE_MAX_SIZE = 1024 * 5;         // 5 MB
    const BANNER_FILE_MAX_SIZE = 1024 * 10;        // 10 MB
    const TRAILER_FILE_MAX_SIZE = 1024 * 1024 * 1;  // 1 GB
    const VIDEO_FILE_MAX_SIZE = 1024 * 1024 * 50; // 50 GB

    protected $hidden = ['video_file', 'thumb_file', 'banner_file', 'trailer_file'];

    public static $fileFields = ['video_file', 'thumb_file', 'banner_file', 'trailer_file'];

    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
        'video_file',
        'trailer_file',
        'thumb_file',
        'banner_file',
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id' => 'string',
        'opened' => 'boolean',
        'year_launched' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * @throws Throwable
     */
    public static function create(array $attributes = []) {
        $files = self::extractFiles($attributes);

        try {
            DB::beginTransaction();
            /** @var Video $obj */
            $obj = static::query()->create($attributes);

            static::handleRelations($obj, $attributes);

            $obj->uploadFiles($files);

            DB::commit();
            return $obj;
        } catch (Exception $e) {
            if (isset($obj)) {
                $obj->deleteFiles($files);
            }
            DB::rollBack();
            throw $e;
        }
    }

    public static function handleRelations(Video $video, array $attributes) {
        if (isset($attributes['categories_id'])) {
            $video->categories()->sync($attributes['categories_id']);
        }

        if (isset($attributes['genres_id'])) {
            $video->genres()->sync($attributes['genres_id']);
        }
    }

    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class)->withTrashed();
    }

    public function genres(): BelongsToMany {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    /**
     * @throws Throwable
     */
    public function update(array $attributes = [], array $options = []) {
        $files = self::extractFiles($attributes);

        try {
            DB::beginTransaction();
            $saved = parent::update($attributes, $options);
            static::handleRelations($this, $attributes);

            if ($saved) {
                $this->uploadFiles($files);
            }

            DB::commit();

            if ($saved && count($files)) {
                $this->deleteOldFiles();
            }

            return $saved;
        } catch (Exception $e) {
            $this->deleteFiles($files);
            DB::rollBack();
            throw $e;
        }
    }

    protected function uploadDir() {
        return $this->id;
    }

    public function getThumbFileUrlAttribute(): ?string {
        return $this->attributes['thumb_file']
            ? $this->getFileUrl($this->attributes['thumb_file'])
            : null;
    }

    public function getBannerFileUrlAttribute(): ?string {
        return $this->attributes['banner_file']
            ? $this->getFileUrl($this->attributes['banner_file'])
            : null;
    }

    public function getTrailerFileUrlAttribute(): ?string {
        return $this->attributes['trailer_file']
            ? $this->getFileUrl($this->attributes['trailer_file'])
            : null;
    }

    public function getVideoFileUrlAttribute(): ?string {
        return $this->attributes['video_file']
            ? $this->getFileUrl($this->attributes['video_file'])
            : null;
    }
}

