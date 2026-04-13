<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /** Public marketplace listing */
    public function index(Request $request): View
    {
        $query = Product::with('producer')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_available', true)
            ->where('stock_kg', '>', 0);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('producer', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($freshness = $request->input('freshness')) {
            $query->where('freshness', $freshness);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('products.index', compact('products'));
    }

    /** Public product detail */
    public function show(int $id): View
    {
        $product = Product::with(['producer', 'questions.consumer', 'reviews.consumer'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        return view('products.show', compact('product'));
    }

    /** Producer — show create form */
    public function create(): View
    {
        return view('producer.products.create');
    }

    /** Producer — store new product */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category'    => ['required', 'in:freshwater,ocean,shellfish,smoked,dried,other'],
            'freshness'   => ['required', 'in:super_fresh,fresh,smoked,dried'],
            'price_per_kg'=> ['required', 'numeric', 'min:0'],
            'stock_kg'    => ['required', 'numeric', 'min:0'],
            'catch_date'  => ['nullable', 'date'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product listed successfully!');
    }

    /** Producer — show edit form */
    public function edit(int $id): View
    {
        $product = Product::findOrFail($id);
        $this->authorizeProduct($product);

        return view('producer.products.edit', compact('product'));
    }

    /** Producer — update product */
    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $this->authorizeProduct($product);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category'    => ['required', 'in:freshwater,ocean,shellfish,smoked,dried,other'],
            'freshness'   => ['required', 'in:super_fresh,fresh,smoked,dried'],
            'price_per_kg'=> ['required', 'numeric', 'min:0'],
            'stock_kg'    => ['required', 'numeric', 'min:0'],
            'catch_date'  => ['nullable', 'date'],
            'is_available'=> ['nullable', 'boolean'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_available'] = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    /** Producer — delete product */
    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $this->authorizeProduct($product);

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product removed.');
    }

    private function authorizeProduct(Product $product): void
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
