@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>
    <form action="{{ route('admin.shop.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label>Name</label>
            <input type="text" name="name" class="form-input w-full" required value="{{ old('name', $product->name) }}">
            @error('name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Description</label>
            <textarea name="description" class="form-textarea w-full" required>{{ old('description', $product->description) }}</textarea>
            @error('description')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Type</label>
            <select name="type" class="form-select w-full" required>
                <option value="digital" @selected(old('type', $product->type)=='digital')>Digital</option>
                <option value="physical" @selected(old('type', $product->type)=='physical')>Physical</option>
            </select>
            @error('type')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Price (₦)</label>
            <input type="number" name="price" class="form-input w-full" required step="0.01" value="{{ old('price', $product->price) }}">
            @error('price')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Stock</label>
            <input type="number" name="stock" class="form-input w-full" required value="{{ old('stock', $product->stock) }}">
            @error('stock')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" class="form-input w-full">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="" class="h-16 w-16 object-cover mt-2 rounded">
            @endif
            @error('image')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Download Link (for digital)</label>
            <input type="url" name="download_link" class="form-input w-full" value="{{ old('download_link', $product->download_link) }}">
            @error('download_link')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-primary w-full">Update Product</button>
    </form>
</div>
@endsection
