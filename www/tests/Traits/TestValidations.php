<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;

trait TestValidations {

    protected function assertInvalidationFields (
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    ) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', " ", $field);

            $response
                ->assertJsonFragment([
                    Lang::get("validation.$rule", ['attribute' => $fieldName] + $ruleParams)
                ]);
        }
    }
}
