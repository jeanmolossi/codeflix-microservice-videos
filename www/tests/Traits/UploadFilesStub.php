<?php

namespace Tests\Traits;

use App\Models\Traits\UploadFiles;
use Illuminate\Database\Eloquent\Model;
use Schema;

class UploadFilesStub extends Model {
    use UploadFiles;

    protected $table = 'upload_file_stubs';
    protected $fillable = ['name', 'file_1', 'file_2'];

    public static $fileFields = ['file_1', 'file_2'];

    public static function makeTable() {
        Schema::create('upload_file_stubs', function ($table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('file_1')->nullable();
            $table->string('file_2')->nullable();
            $table->timestamps();
        });
    }

    public static function dropTable() {
        Schema::dropIfExists('upload_file_stubs');
    }

    protected function uploadDir(): string {
        return "1";
    }
}
