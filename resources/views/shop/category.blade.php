@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <a href="{{ route('shop.index') }}" class="text-blue-500 hover:underline">&larr; Back to categories</a>
    <h1 class="text-2xl font-bold mb-6 mt-2">{{ $category->title }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($category->articles as $article)
            <a href="{{ route('shop.checkout', $article->id) }}" class="block bg-white rounded shadow hover:shadow-lg transition p-4">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-40 object-cover rounded mb-4">
                @endif
                <h2 class="text-lg font-semibold mb-2">{{ $article->title }}</h2>
                <p class="text-gray-600 mb-2">{{ $article->description }}</p>
                <p class="text-green-700 font-bold">${{ $article->price }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
