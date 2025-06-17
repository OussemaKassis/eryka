@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Categories</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('shop.category', $category->id) }}" class="block bg-white rounded shadow hover:shadow-lg transition p-4">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->title }}" class="w-full h-40 object-cover rounded mb-4">
                @endif
                <h2 class="text-lg font-semibold mb-2">{{ $category->title }}</h2>
                <p class="text-gray-600">{{ $category->description }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
