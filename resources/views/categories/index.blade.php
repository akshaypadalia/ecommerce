@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            Add Category
        </a>
    </div>
    <ul class="list-group">
        @foreach($categoriesWithProducts as $item)
            @php
                $category = $item['category'];
                $hasProducts = $item['hasProducts'];
            @endphp
            @include('categories.category_item', ['category' => $category, 'hasProducts' => $hasProducts])
        @endforeach
    </ul>
</div>    
@endsection
