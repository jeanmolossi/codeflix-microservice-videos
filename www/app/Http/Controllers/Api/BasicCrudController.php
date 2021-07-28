<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BasicCrudController extends Controller
{
    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    protected abstract function model();
    protected abstract function rulesStore();
    protected abstract function rulesUpdate();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request) {
        $validatedData = $this->validate($request, $this->rulesStore());

        $obj = $this->model()::create($validatedData);
        $obj->refresh();

        return $obj;
    }

    protected function findOrFail($id) {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();

        return $this->model()::where($keyName, $id)->firstOrFail();
    }

    public function show($id) {
        return $this->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $obj = $this->findOrFail($id);

        $validatedData = $this->validate($request, $this->rulesUpdate());

        $obj->update($validatedData);

        return $obj;
    }

    public function destroy($id): Response {
        $obj = $this->findOrFail($id);
        $obj->delete();

        return response()->noContent();
    }

}
