<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $category = Category::create($request->all());
        $category->refresh();

        return $category;
    }

    public function show(Category $category): Category {
        return $category;
    }

    public function update(Request $request, Category $category): Category {
        $this->validate($request, $this->rules);

        $category->update($request->all());

        return $category;
    }

    public function destroy(Category $category): Response {
        $category->delete();

        return response()->noContent();
    }
}
