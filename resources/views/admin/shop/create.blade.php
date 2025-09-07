@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>
    <form action="{{ route('admin.shop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name" class="form-input w-full" required value="{{ old('name') }}">
            @error('name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Description</label>
            <textarea name="description" class="form-textarea w-full" required>{{ old('description') }}</textarea>
            @error('description')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Type</label>
            <select name="type" class="form-select w-full" required>
                <option value="digital" @selected(old('type')=='digital')>Digital</option>
                <option value="physical" @selected(old('type')=='physical')>Physical</option>
            </select>
            @error('type')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Price (₦)</label>
            <input type="number" name="price" class="form-input w-full" required step="0.01" value="{{ old('price') }}">
            @error('price')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Stock</label>
            <input type="number" name="stock" class="form-input w-full" required value="{{ old('stock') }}">
            @error('stock')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" class="form-input w-full">
            @error('image')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Download Link (for digital)</label>
            <input type="url" name="download_link" class="form-input w-full" value="{{ old('download_link') }}">
            @error('download_link')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-primary w-full">Create Product</button>
    </form>
</div>
@endsection
