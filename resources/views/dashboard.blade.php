<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    
                    <!-- Links to Categories and Products pages -->
                    <div class="mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-primary">Categories</a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary ml-2">Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </x-slot>

    
</x-app-layout>
