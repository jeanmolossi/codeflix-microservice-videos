<?php

namespace Tests\Traits;

use App\Models\Traits\UploadFiles;
use Illuminate\Http\UploadedFile;
use Storage;

trait TestUploads {

    protected function assertInvalidationFile($field, $extension, $maxSize, $rule, $ruleParams = []) {
        $routes = [
            [
                'method' => 'POST',
                'route' => $this->routeStore()
            ],
            [
                'method' => 'PUT',
                'route' => $this->routeUpdate()
            ]
        ];

        foreach ($routes as $route) {
            $file = UploadedFile::fake()->create("$field.1$extension");
            $response = $this->json(
                $route['method'],
                $route['route'],
                [$field => $file]
            );

            $this->assertInvalidationFields($response, [$field], $rule, $ruleParams);

            $file = UploadedFile::fake()->create("$field.$extension")->size($maxSize + 1);
            $response = $this->json(
              $route['method'],
              $route['route'],
              [$field => $file]
            );

            $this->assertInvalidationFields($response, [$field], 'max.file', ['max' => $maxSize]);
        }
    }

    /**
     * @param UploadedFile[] $files
     */
    protected function assertFilesExistsInStorage($model, array $files) {

        /** @param UploadFiles $model */
        foreach ($files as $file) {
            var_dump($file->getClientOriginalName());
            var_dump($file->hashName());
            Storage::assertExists(
                $model->relativeFilePath($file->hashName())
            );
        }
    }

}
