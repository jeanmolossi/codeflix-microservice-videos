<?php

namespace Tests\Traits;

use App\Models\Traits\UploadFiles;
use Illuminate\Database\Eloquent\Model;

class UploadFilesStub extends Model {
    use UploadFiles;

    public static $fileFields = ['file_1', 'file_2'];

    protected function uploadDir() {
        return "1";
    }
}
