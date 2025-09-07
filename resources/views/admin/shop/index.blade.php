@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Manage Products</h1>
    <a href="{{ route('admin.shop.create') }}" class="btn btn-primary mb-4">Add New Product</a>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="p-2">Image</th>
                <th class="p-2">Name</th>
                <th class="p-2">Type</th>
                <th class="p-2">Price</th>
                <th class="p-2">Stock</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-t">
                <td class="p-2"><img src="{{ $product->image_url }}" alt="" class="h-12 w-12 object-cover rounded"></td>
                <td class="p-2">{{ $product->name }}</td>
                <td class="p-2">{{ ucfirst($product->type) }}</td>
                <td class="p-2">₦{{ $product->price }}</td>
                <td class="p-2">{{ $product->stock }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.shop.edit', $product) }}" class="btn btn-outline">Edit</a>
                    <form action="{{ route('admin.shop.destroy', $product) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline text-red-600" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
