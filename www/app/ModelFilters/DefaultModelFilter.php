<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Str;

abstract class DefaultModelFilter extends ModelFilter {
    protected $sortable = [];

    public function setup() {
        $this->blacklistMethod('isSortable');

        $hasNoSort = $this->input('sort', '') === '';

        if ($hasNoSort) {
            $this->orderByDesc('created_at');
        }
    }

    public function sort($column) {
        if (method_exists($this, $method = "sortBy" . Str::studly($column))) {
            $this->$method();
        }

        if ($this->isSortable($column)) {
            $dir = strtolower($this->input('dir')) === 'asc' ? 'ASC' : 'DESC';
            $this->orderBy($column, $dir);
        }
    }

    protected function isSortable($column): bool {
        return in_array($column, $this->sortable);
    }
}
