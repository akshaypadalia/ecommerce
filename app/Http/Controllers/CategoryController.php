<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
   
public function index()
{
    $categories = Category::with(['children', 'products'])->whereNull('parent_id')->get();
    $categoriesWithProducts = $categories->map(function ($category) {
        return [
            'category' => $category,
            'hasProducts' => $this->hasProductsInCategory($category),
        ];
    });

    return view('categories.index', compact('categoriesWithProducts'));
}

private function hasProductsInCategory($category)
{
    if ($category->products->isNotEmpty()) {
        return true;
    }

    foreach ($category->children as $child) {
        if ($child->products->isNotEmpty()) {
            return true;
        }
    }
    return false;
}

public function hasProductsInChildrenList($category)
{
    if ($category->products->count() > 0) {
        return true;
    }
    foreach ($category->children as $child) {
        if ($this->hasProductsInChildrenList($child)) {
            return true;
        }
    }

    return false;
}
    public function create()
    {
        $categories = Category::all();
        $nestedCategories = $this->buildCategoryTree($categories);
        
        return view('categories.create', compact('nestedCategories'));
    }
    
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        $nestedCategories = $this->buildCategoryTree($categories);
        
        return view('categories.edit', compact('category', 'nestedCategories'));
    }
    
    private function buildCategoryTree($categories, $parentId = null, $prefix = '')
    {
        $tree = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $tree[] = ['id' => $category->id, 'name' => $prefix . $category->name];
                $tree = array_merge($tree, $this->buildCategoryTree($categories, $category->id, $prefix . '— '));
            }
        }
        return $tree;
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }
    public function destroy($id)
    {
        
        $category = Category::findOrFail($id);
    
       
        if ($category->products->count() > 0) {
           
            return redirect()->route('categories.index')->with('error', 'Cannot delete category. Products exist.');
        }
    
       
        if ($this->hasProductsInChildren($category)) {
            
            return redirect()->route('categories.index')->with('error', 'Cannot delete category. One or more child categories have products.');
        }
    
        
        $category->delete();
    
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
    
    
    private function hasProductsInChildren($category)
    {
        foreach ($category->children as $child) {
            
            if ($child->products->count() > 0) {
                return true; 
            }
    
           
            if ($this->hasProductsInChildren($child)) {
                return true; 
            }
        }
        return false; 
    }
    
}