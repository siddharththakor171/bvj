<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryCategory;
use App\Models\JewelryProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->syncExistingProductCategories();

        $categories = JewelryCategory::query()->withCount('products')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:jewelry_categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        JewelryCategory::create([...$validated, 'slug' => Str::slug($validated['name'])]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, JewelryCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('jewelry_categories', 'name')->ignore($category)],
            'description' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($category, $validated): void {
            $oldName = $category->name;
            $category->update([...$validated, 'slug' => Str::slug($validated['name'])]);
            JewelryProduct::where('category', $oldName)->update(['category' => $category->name]);
        });

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(JewelryCategory $category): RedirectResponse
    {
        if (JewelryProduct::where('category', $category->name)->exists()) {
            return redirect()->route('admin.categories.index')->with('error', 'Move or rename products in this category before deleting it.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    private function syncExistingProductCategories(): void
    {
        JewelryProduct::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->each(fn (string $name) => JewelryCategory::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]));
    }
}
