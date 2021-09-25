<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

abstract class BasicCrudController extends Controller {

    protected $perPage = 15;

    public function index(Request $request) {
        $perPage = (int)$request->get('per_page', $this->perPage);
        $hasFilter = in_array(Filterable::class, class_uses($this->model()));

        $query = $this->queryBuilder();

        if ($hasFilter) {
            $query = $query->filter($request->all());
        }

        $data = $request->has('all') || !$this->perPage
            ? $query->get()
            : $query->paginate($perPage);

        $resourceCollection = $this->resourceCollection();

        $refClass = new ReflectionClass($this->resourceCollection());

        return $refClass->isSubclassOf(CategoryResource::class)
            ? new $resourceCollection($data)
            : $resourceCollection::collection($data);
    }

    protected abstract function model();

    protected abstract function resourceCollection();

    /**
     * @throws ValidationException
     */
    public function store(Request $request) {
        $validatedData = $this->validate($request, $this->rulesStore());

        $obj = $this->queryBuilder()->create($validatedData);
        $obj->refresh();

        $resource = $this->resource();

        return new $resource($obj);
    }

    protected abstract function rulesStore();

    protected abstract function resource();

    public function show($id) {
        $resource = $this->resource();
        return new $resource($this->findOrFail($id));
    }

    protected function findOrFail($id) {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();

        return $this->queryBuilder()->where($keyName, $id)->firstOrFail();
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id) {
        $obj = $this->findOrFail($id);

        $validatedData = $this->validate($request, $this->rulesUpdate());

        $obj->update($validatedData);

        $resource = $this->resource();

        return new $resource($obj);
    }

    protected abstract function rulesUpdate();

    public function destroy($id): Response {
        $obj = $this->findOrFail($id);
        $obj->delete();

        return response()->noContent();
    }

    protected function queryBuilder(): Builder {
        return $this->model()::query();
    }

}
