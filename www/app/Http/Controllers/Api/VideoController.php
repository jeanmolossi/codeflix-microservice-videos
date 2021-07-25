<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Rules\GenresHasCategoriesRule;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class VideoController extends BasicCrudController {
    private $rules;

    public function __construct() {
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:' . implode(',', Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL',
            'genres_id' => [
                'required',
                'array',
                'exists:genres,id,deleted_at,NULL'
            ]
        ];
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(Request $request): Video {
        $this->addRuleIfGenreHasCategories($request);

        $validatedData = $this->validate($request, $this->rulesStore());

        $obj = $this->model()::create($validatedData);

        $obj->refresh();

        return $obj;
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(Request $request, $id) {
        $obj = $this->findOrFail($id);

        $this->addRuleIfGenreHasCategories($request);

        $validatedData = $this->validate($request, $this->rulesUpdate());

        $obj->update($validatedData);

        return $obj;
    }

    protected function addRuleIfGenreHasCategories(Request $request) {
        $categoriesId = $request->get('categories_id');

        $categoriesId = is_array($categoriesId) ? $categoriesId : [];

        $this->rules['genres_id'][] = new GenresHasCategoriesRule(
            $categoriesId
        );
    }

    protected function model(): string {
        return Video::class;
    }

    protected
    function rulesStore(): array {
        return $this->rules;
    }

    protected
    function rulesUpdate(): array {
        return $this->rules;
    }
}

