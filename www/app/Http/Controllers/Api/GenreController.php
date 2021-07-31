<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class GenreController extends BasicCrudController {

    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean',
        'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL'
    ];

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(Request $request): Genre {
        $validatedData = $this->validate($request, $this->rulesStore());

        $self = $this;

        /** @var Genre $obj */
        $obj = DB::transaction(function () use ($request, $validatedData, $self) {
            $obj = $this->model()::create($validatedData);
            $self->handleRelations($obj, $request);

            return $obj;
        });

        $obj->refresh();

        return $obj;
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(Request $request, $id) {
        $obj = $this->findOrFail($id);

        $validatedData = $this->validate($request, $this->rulesUpdate());

        $self = $this;

        return DB::transaction(function () use ($request, $validatedData, $obj, $self) {
            $obj->update($validatedData);
            $self->handleRelations($obj, $request);

            return $obj;
        });
    }

    protected function handleRelations(Genre $genre, Request $request) {
        $genre->categories()->sync($request->get('categories_id'));
    }

    protected function model(): string {
        return Genre::class;
    }

    protected function rulesStore(): array {
        return $this->rules;
    }

    protected function rulesUpdate(): array {
        return $this->rules;
    }

    protected function resource(): string {
        return GenreResource::class;
    }

    protected function resourceCollection(): string {
        return $this->resource();
    }


}
