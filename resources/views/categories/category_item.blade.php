<li class="list-group-item">
    <strong>{{ $category->name }}</strong>

   
    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm float-end ms-2">Edit</a>

    
    @if(!$hasProducts) 
        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm float-end">Delete</button>
        </form>
    @endif

   
    @if($category->children->count() > 0)
        <button class="btn btn-secondary btn-sm mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#category-{{ $category->id }}" aria-expanded="false" aria-controls="category-{{ $category->id }}">
            View Subcategories
        </button>

        <div class="collapse mt-2" id="category-{{ $category->id }}">
            <ul class="list-group">
                @foreach($category->children as $child)
                @include('categories.category_item', ['category' => $child, 'hasProducts' => $hasProducts])
                @endforeach
            </ul>
        </div>
    @endif
</li>
