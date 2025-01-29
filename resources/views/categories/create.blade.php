@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Category</h2>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Category (Optional)</label>
            <select name="parent_id" class="form-control">
                <option value="">None</option>
                @foreach($nestedCategories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Category</button>
    </form>
</div>
@endsection
