<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\CategoryStub;
use Tests\Stubs\Resources\CategoryStubResource;

class CategoryControllerStub extends BasicCrudController
{
    protected function model(): string {
        return CategoryStub::class;
    }

    protected function rulesStore(): array {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable'
        ];
    }

    protected function rulesUpdate(): array {
        return $this->rulesStore();
    }

    protected function resource(): string {
        return CategoryStubResource::class;
    }

    protected function resourceCollection(): string {
        return $this->resource();
    }
}
