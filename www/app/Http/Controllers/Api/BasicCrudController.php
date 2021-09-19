<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

abstract class BasicCrudController extends Controller {

    protected $paginationSize = 15;

    protected abstract function model();

    protected abstract function rulesStore();

    protected abstract function rulesUpdate();

    protected abstract function resource();

    protected abstract function resourceCollection();

    public function index() {
        $data = !$this->paginationSize
            ? $this->model()::all()
            : $this->model()::paginate($this->paginationSize);

        $resourceCollection = $this->resourceCollection();

        $refClass = new ReflectionClass($this->resourceCollection());

        return $refClass->isSubclassOf(CategoryResource::class)
            ? new $resourceCollection($data)
            : $resourceCollection::collection($data);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request) {
        $validatedData = $this->validate($request, $this->rulesStore());

        $obj = $this->model()::create($validatedData);
        $obj->refresh();

        $resource = $this->resource();

        return new $resource($obj);
    }

    protected function findOrFail($id) {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();

        return $this->model()::where($keyName, $id)->firstOrFail();
    }

    public function show($id) {
        $resource = $this->resource();
        return new $resource($this->findOrFail($id));
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

    public function destroy($id): Response {
        $obj = $this->findOrFail($id);
        $obj->delete();

        return response()->noContent();
    }

}
