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

        $self = $this;

        /** @var Video $obj */
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

        $this->addRuleIfGenreHasCategories($request);

        $validatedData = $this->validate($request, $this->rulesUpdate());

        $self = $this;

        return DB::transaction(function () use ($request, $validatedData, $self, $obj) {
            $obj->update($validatedData);
            $self->handleRelations($obj, $request);

            return $obj;
        });
    }

    protected function addRuleIfGenreHasCategories(Request $request) {
        $categoriesId = $request->get('categories_id');

        $categoriesId = is_array($categoriesId) ? $categoriesId : [];

        $this->rules['genres_id'][] = new GenresHasCategoriesRule(
            $categoriesId
        );
    }

    protected function handleRelations(Video $video, Request $request) {
        $video->categories()->sync($request->get('categories_id'));
        $video->genres()->sync($request->get('genres_id'));
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

