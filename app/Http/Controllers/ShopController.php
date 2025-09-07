<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

use Illuminate\Support\Facades\Storage;
use HTMLPurifier;

class ShopController extends Controller
{
    // Shop page for users
    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = Product::query();
        if ($type && in_array($type, ['digital', 'physical'])) {
            $query->where('type', $type);
        }
        $products = $query->paginate(12);
        return view('shop.index', compact('products', 'type'));
    }

    // Admin: List all products
    public function adminIndex()
    {
        $products = Product::orderByDesc('id')->paginate(20);
        return view('admin.shop.index', compact('products'));
    }

    // Admin: Show create form
    public function create()
    {
        return view('admin.shop.create');
    }

    // Admin: Store new product
    public function store(Request $request)
    {
        \Log::info('ShopController@store', [
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'request_token' => $request->input('_token'),
            'user_id' => auth()->id(),
        ]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:digital,physical',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'download_link' => 'nullable|url',
            'stock' => 'required|integer|min:0',
        ]);

        $purifier = new \HTMLPurifier();
        $validated['description'] = $purifier->purify($validated['description']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        Product::create($validated);
        return redirect()->route('admin.shop.index')->with('success', 'Product created successfully.');
    }

    // Admin: Show edit form
    public function edit(Product $product)
    {
        return view('admin.shop.edit', compact('product'));
    }

    // Admin: Update product
    public function update(Request $request, Product $product)
    {
        \Log::info('ShopController@update', [
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'request_token' => $request->input('_token'),
            'user_id' => auth()->id(),
        ]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:digital,physical',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'download_link' => 'nullable|url',
            'stock' => 'required|integer|min:0',
        ]);

        $purifier = new \HTMLPurifier();
        $validated['description'] = $purifier->purify($validated['description']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                @unlink(public_path($product->image_url));
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $product->update($validated);
        return redirect()->route('admin.shop.index')->with('success', 'Product updated successfully.');
    }

    // Admin: Delete product
    public function destroy(Product $product)
    {
        \Log::info('ShopController@destroy', [
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'request_token' => request()->input('_token'),
            'user_id' => auth()->id(),
        ]);
        if ($product->image_url && file_exists(public_path($product->image_url))) {
            @unlink(public_path($product->image_url));
        }
        $product->delete();
        return redirect()->route('admin.shop.index')->with('success', 'Product deleted.');
    }
}
