<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SubCategory;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'color', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
