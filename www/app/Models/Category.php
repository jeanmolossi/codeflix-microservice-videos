<?php

namespace App\Models;

use App\ModelFilters\CategoryFilter;
use App\Models\Traits\Uuid as TraitsUuid;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,
        SoftDeletes,
        TraitsUuid,
        Filterable;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];

    public $incrementing = false;

    public function modelFilter(): ?string {
        return $this->provideFilter(CategoryFilter::class);
    }
}
