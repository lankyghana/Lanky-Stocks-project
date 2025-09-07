@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Shop</h1>
    <div class="mb-4 flex gap-2">
        <a href="{{ route('shop.index') }}" class="btn {{ !$type ? 'btn-primary' : 'btn-outline' }}">All</a>
        <a href="{{ route('shop.index', ['type' => 'digital']) }}" class="btn {{ $type == 'digital' ? 'btn-primary' : 'btn-outline' }}">Digital</a>
        <a href="{{ route('shop.index', ['type' => 'physical']) }}" class="btn {{ $type == 'physical' ? 'btn-primary' : 'btn-outline' }}">Physical</a>
    </div>
    <div id="shop-app">
        <shop-grid :products='@json($products->items())'></shop-grid>
        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/shop.js')
@endpush
