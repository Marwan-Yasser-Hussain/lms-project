<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['category_id'] = $category->id;
        
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $count = 1;
        while (SubCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        SubCategory::create($validated);

        return back()->with('success', 'Sub-category created!');
    }

    public function update(Request $request, Category $category, SubCategory $subcategory)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $count = 1;
        while (SubCategory::where('slug', $slug)->where('id', '!=', $subcategory->id)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        $subcategory->update($validated);

        return back()->with('success', 'Sub-category updated!');
    }

    public function destroy(Category $category, SubCategory $subcategory)
    {
        $subcategory->delete();

        return back()->with('success', 'Sub-category deleted.');
    }
}
