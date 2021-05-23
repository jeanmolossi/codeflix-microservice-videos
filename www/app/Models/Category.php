<?php

namespace App\Models;

use App\Models\Traits\Uuid as TraitsUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,
        SoftDeletes,
        TraitsUuid;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string'
    ];
}
