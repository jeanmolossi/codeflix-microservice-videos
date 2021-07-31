<?php

namespace App\Models\Traits;

use Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;

trait UploadFiles {

    public $oldFiles = [];

    public static function bootUploadFiles() {
        static::updating(function (Model $model) {
            $fieldsUpdated = array_keys($model->getDirty());
            $filesUpdated = array_intersect($fieldsUpdated, self::$fileFields);

            $filesFiltered = Arr::where($filesUpdated, function ($fileField) use ($model) {
                return $model->getOriginal($fileField);
            });

            $model->oldFiles = array_map(function ($fileField) use ($model) {
                return $model->getOriginal($fileField);
            }, $filesFiltered);
        });
    }

    public static function extractFiles(array &$attributes = []): array {
        $files = [];

        foreach (self::$fileFields as $file) {
            if (isset($attributes[$file]) && $attributes[$file] instanceof UploadedFile) {
                $files[] = $attributes[$file];
                $attributes[$file] = ($attributes[$file])->hashName();
            }
        }

        return $files;
    }

    /**
     * @param UploadedFile[] $files
     */
    public function uploadFiles(array $files) {
        foreach ($files as $file) {
            $this->uploadFile($file);
        }
    }

    public function uploadFile(UploadedFile $file) {
        $file->store(
            $this->uploadDir()
        );
    }

    public function deleteOldFiles() {
        $this->deleteFiles($this->oldFiles);
    }

    /**
     * @param UploadedFile[] $files
     */
    public function deleteFiles(array $files) {
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
    }

    /**
     * @param string | UploadedFile $file
     */
    public function deleteFile($file) {
        $filename = $file instanceof UploadedFile ? $file->hashName() : $file;
        Storage::delete("{$this->uploadDir()}/{$filename}");
    }

    public function relativeFilePath(string $hashName): string {
        return "{$this->uploadDir()}/{$hashName}";
    }

    protected abstract function uploadDir();

    protected function getFileUrl(string $filename): string {
        return Storage::url("{$this->relativeFilePath($filename)}");
    }
}
