@extends('layouts.app')
@php
    // Function to reverse the category hierarchy
    function getCategoryHierarchyList($category) {
        $hierarchy = [];
        while ($category) {
            array_unshift($hierarchy, $category->name);
            $category = $category->parent; // Assuming you have 'parent' relationship set up in the model
        }
        return implode(' > ', $hierarchy);
    }
@endphp
@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Products</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                Add Product
            </a>
        </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Categories</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productsWithCategories as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        

                        @foreach($product->categories as $category)
                            <p>{{ getCategoryHierarchyList($category) }}</p>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
