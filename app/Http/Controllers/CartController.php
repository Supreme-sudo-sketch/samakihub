<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    const DELIVERY_FEE = 200.00;

    public function index(): View
    {
        $rows = Cart::with(['product.producer'])
            ->where('user_id', auth()->id())
            ->get();

        $cart = [];
        foreach ($rows as $row) {
            if (! $row->product) continue;
            $cart[$row->product_id] = [
                'name'        => $row->product->name,
                'qty'         => (float) $row->quantity_kg,
                'price_per_kg'=> (float) $row->product->price_per_kg,
                'fisherman'   => $row->product->producer->name ?? 'Unknown',
                'image'       => $row->product->image,
                'max_stock'   => (float) $row->product->stock_kg,
            ];
        }

        $subtotal    = collect($cart)->sum(fn($i) => $i['price_per_kg'] * $i['qty']);
        $deliveryFee = $subtotal > 0 ? self::DELIVERY_FEE : 0;
        $total       = $subtotal + $deliveryFee;

        return view('consumer.cart.index', compact('cart', 'deliveryFee', 'total'));
    }

    public function add(Request $request, int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'qty' => ['required', 'numeric', 'min:0.5', 'max:' . $product->stock_kg],
        ]);

        Cart::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $productId],
            ['quantity_kg' => $request->input('qty', 1)]
        );

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'qty' => ['required', 'numeric', 'min:0.5'],
        ]);

        $qty = min((float) $request->input('qty'), (float) $product->stock_kg);

        Cart::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $productId],
            ['quantity_kg' => $qty]
        );

        return back();
    }

    public function remove(int $productId): RedirectResponse
    {
        Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
