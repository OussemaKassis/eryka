@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8 max-w-2xl">
    <a href="{{ route('shop.category', $article->category->id) }}" class="text-blue-500 hover:underline">&larr; Back to {{ $article->category->title }}</a>
    <div class="bg-white rounded shadow p-6 mt-4">
        <div class="flex flex-col md:flex-row gap-6">
            @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full md:w-64 h-64 object-cover rounded mb-4">
            @endif
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $article->title }}</h1>
                <p class="text-gray-700 mb-2">{{ $article->description }}</p>
                <div class="prose mb-4">{!! $article->detail !!}</div>
                <p class="text-green-700 font-bold text-lg mb-4">${{ $article->price }}</p>
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
                @endif
                <form action="{{ route('shop.order.submit', $article->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="quantity" min="1" value="1" required placeholder="Quantity" class="border rounded p-2 w-full">
                        <input type="text" name="customer_first_name" required placeholder="First Name" class="border rounded p-2 w-full">
                        <input type="text" name="customer_last_name" required placeholder="Last Name" class="border rounded p-2 w-full">
                        <input type="text" name="city" required placeholder="City" class="border rounded p-2 w-full">
                        <input type="email" name="email" required placeholder="Email" class="border rounded p-2 w-full">
                        <input type="text" name="phone_number" required placeholder="Phone Number" class="border rounded p-2 w-full">
                    </div>
                    <textarea name="address" required placeholder="Address" class="border rounded p-2 w-full"></textarea>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
