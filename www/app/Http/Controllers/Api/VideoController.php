<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{
    private $rules;

    public function __construct() {
        $this->rules = [];
    }

    protected function model(): string {
        return Video::class;
    }

    protected function rulesStore(): array {
        return $this->rules;
    }

    protected function rulesUpdate(): array {
        return $this->rules;
    }
}
