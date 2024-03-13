<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StaffType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10)->withQueryString();
        return view('category.admin.manage', compact('categories'));
        
    }

    public function addCategory(Request $request)
{
    $request->validate([
        'categoryName' => [
            'required',
            'unique:categories,name',
            Rule::unique('categories', 'name')
        ],
        'categoryStaffType' => 'required'
    ]);

    Category::create([
        'name' => $request->categoryName,
        'staffType_id' => $request->categoryStaffType,
    ]);

    return redirect()->route('categories.list');
}

    public function showAddCategoryForm()
    {
        $staffTypes = StaffType::all();
        return view('category.admin.create', compact('staffTypes'));
    }

    public function updateFormCategory($id)
    {
        $category = Category::findOrFail($id);
        $staffTypes = StaffType::all();
        return view('category.admin.edit', compact('category', 'staffTypes'));
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'categoryName' => [
                'required',
                Rule::unique('categories', 'name')->ignore($id),
            ],
            'categoryStaffType' => 'required'
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->categoryName,
            'staffType_id' => $request->categoryStaffType
        ]);

        return redirect()->route('categories.list');
    }

    public function deleteCategory(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->delete();

        return redirect()->route('categories.list');
    }
}
