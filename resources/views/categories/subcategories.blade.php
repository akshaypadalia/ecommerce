<ul class="list-group">
    @foreach($category->children as $child)
        <li class="list-group-item">
            <strong>{{ $child->name }}</strong>

            
            <a href="{{ route('categories.edit', $child->id) }}" class="btn btn-warning btn-sm ml-2">Edit</a>

            
            <form action="{{ route('categories.destroy', $child->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm ml-2" 
                    @if($child->products->count() > 0) 
                        disabled
                    @endif
                >
                    @if($child->products->count() > 0)
                        Cannot Delete (Products exist)
                    @else
                        Delete
                    @endif
                </button>
            </form>

           
            @if($child->children->count() > 0)
                @include('categories.subcategories', ['category' => $child])
            @endif
        </li>
    @endforeach
</ul>
