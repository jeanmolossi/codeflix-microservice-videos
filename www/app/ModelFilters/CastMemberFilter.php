<?php

namespace App\ModelFilters;


class CastMemberFilter extends DefaultModelFilter {
    public $sortable = ['name', 'is_active', 'created_at'];

    public function search($search) {
        $this->query->where(
            'name',
            'LIKE',
            "%$search%"
        );
    }
}
