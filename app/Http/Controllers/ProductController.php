<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
   
    public function index()
{
    $products = Product::with('categories')->get();

   
    $productsWithCategories = $products->map(function ($product) {
        $product->category_names = $this->getCategoryHierarchy($product->categories);
        return $product;
    });

    return view('products.index', compact('productsWithCategories'));
}

private function getCategoryHierarchy($categories)
{
    $categoryNames = [];

    foreach ($categories as $category) {
        $categoryNames[] = $this->buildCategoryTreeShow($category);
    }

    
    return implode(' || ', $categoryNames);
}

private function buildCategoryTreeShow($category, $prefix = '')
{
    $categoryName = $prefix . $category->name;

    
    if ($category->parent_id) {
        $parentCategory = Category::find($category->parent_id);
        $categoryName = $this->buildCategoryTreeShow($parentCategory, $category->name . ' > ' . $prefix);
    }

    return $categoryName;

    
}
   
    public function create()
{
    $categories = Category::all();
    $nestedCategories = $this->buildCategoryTree($categories);
    
    return view('products.create', compact('nestedCategories'));
}

public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all();
    $nestedCategories = $this->buildCategoryTree($categories);
    
    return view('products.edit', compact('product', 'nestedCategories'));
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
            'price' => 'required|numeric',
            'categories' => 'required|array',
        ]);

        $product = Product::create($request->only(['name', 'description', 'price']));
        $product->categories()->attach($request->categories);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function update(Request $request, $id)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'categories' => 'required|array',
        ]);

        
        $product = Product::findOrFail($id);

       
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

       
        $product->categories()->sync($request->input('categories'));

       
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
