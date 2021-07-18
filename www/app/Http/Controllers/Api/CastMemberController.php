<?php

namespace App\Http\Controllers\Api;

use App\Models\CastMember;

class CastMemberController extends BasicCrudController {
    private $rules;

    public function __construct() {
        $this->rules = [
            'name' => 'required|max:255',
            'type' => 'required|in:' . implode(',', [CastMember::TYPE_ACTOR, CastMember::TYPE_DIRECTOR])
        ];
    }

    protected function model(): string {
        return CastMember::class;
    }

    protected function rulesStore(): array {
        return $this->rules;
    }

    protected function rulesUpdate(): array {
        return $this->rules;
    }
}
