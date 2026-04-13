<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Dashboard\ProducerDashboardController;
use App\Http\Controllers\Dashboard\ConsumerDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Generic dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'producer' => redirect()->route('producer.dashboard'),
        'delivery' => redirect()->route('delivery.dashboard'),
        default    => redirect()->route('consumer.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                  [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/approve',    [AdminController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{id}/suspend',    [AdminController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{id}/role',       [AdminController::class, 'changeUserRole'])->name('users.role');
    Route::delete('/users/{id}',          [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/products',               [AdminController::class, 'products'])->name('products');
    Route::get('/reviews',                [AdminController::class, 'reviews'])->name('reviews');
    Route::get('/questions',              [AdminController::class, 'questions'])->name('questions');
    Route::post('/questions/{id}/answer', [AdminController::class, 'answerQuestion'])->name('questions.answer');
    Route::get('/orders',                 [AdminController::class, 'orders'])->name('orders');
    Route::get('/statistics',                        [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/withdrawals',                       [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/approve',         [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject',          [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
});

// Producer routes
Route::middleware(['auth', 'role:producer'])->prefix('producer')->name('producer.')->group(function () {
    Route::get('/dashboard', [ProducerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/payouts',   [WithdrawalController::class, 'index'])->name('payouts');
    Route::post('/payouts',  [WithdrawalController::class, 'store'])->name('payouts.store');
});

// Products — public browse
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Products — producer-only write actions (must be before wildcard /products/{id})
Route::middleware(['auth', 'role:producer'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Products — public detail (after /products/create to avoid route shadowing)
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Reviews — consumer submits a review
Route::middleware(['auth', 'role:consumer'])->group(function () {
    Route::post('/products/{id}/reviews',  [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}',         [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Questions — consumer posts a question on a product
Route::middleware(['auth', 'role:consumer'])->group(function () {
    Route::post('/products/{id}/questions', function (\Illuminate\Http\Request $request, int $id) {
        $product = \App\Models\Product::findOrFail($id);
        $request->validate(['question' => ['required', 'string', 'max:1000']]);
        \App\Models\Question::create([
            'product_id'  => $product->id,
            'consumer_id' => auth()->id(),
            'producer_id' => $product->user_id,
            'question'    => $request->question,
        ]);
        return back()->with('success', 'Your question has been sent to the producer!');
    })->name('questions.store');
});

// Questions — producer answers a question on their product
Route::middleware(['auth', 'role:producer'])->patch('/questions/{id}/answer', function (\Illuminate\Http\Request $request, int $id) {
    $question = \App\Models\Question::findOrFail($id);
    if ($question->producer_id !== auth()->id()) abort(403);
    $request->validate(['answer' => ['required', 'string', 'max:2000']]);
    $question->update(['answer' => $request->answer]);
    return back()->with('success', 'Answer posted!');
})->name('questions.answer');

// Consumer routes — cart, orders
Route::middleware(['auth', 'role:consumer'])->group(function () {
    // Dashboard
    Route::get('/consumer/dashboard', [ConsumerDashboardController::class, 'index'])->name('consumer.dashboard');

    // Cart
    Route::get('/cart',                        [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}',       [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{productId}',   [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{productId}',  [CartController::class, 'remove'])->name('cart.remove');

    // Orders
    Route::post('/orders',       [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders',        [OrderController::class, 'index'])->name('orders.index');
});

// Order show — both consumers and delivery agents can view
Route::middleware('auth')->get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

// Latest GPS location for an order (JSON — consumer polls this for live map updates)
Route::middleware('auth')->get('/orders/{id}/location/latest', function (int $id) {
    $order = \App\Models\Order::with('latestLocation')->findOrFail($id);
    $user  = auth()->user();
    if ($user->role === 'consumer' && $order->consumer_id !== $user->id) abort(403);
    if ($user->role === 'delivery' && $order->delivery_agent_id !== $user->id) abort(403);
    $loc = $order->latestLocation;
    if (! $loc) return response()->json(['found' => false]);
    return response()->json([
        'found'      => true,
        'latitude'   => (float) $loc->latitude,
        'longitude'  => (float) $loc->longitude,
        'updated_at' => $loc->created_at->diffForHumans(),
    ]);
})->name('orders.location.latest');

// Payment — consumer only
Route::middleware(['auth', 'role:consumer'])->group(function () {
    Route::get('/orders/{id}/pay',            [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/orders/{id}/pay',           [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/orders/{id}/payment-status', [PaymentController::class, 'status'])->name('payment.status');
});

// PesaPal callback — no auth required (PesaPal servers call this)
Route::match(['get', 'post'], '/api/pesapal/callback', [PaymentController::class, 'pesapalCallback'])->name('payment.pesapal.callback');

// Delivery routes
Route::middleware(['auth', 'role:delivery'])->group(function () {
    Route::get('/delivery/dashboard',                        [DeliveryController::class, 'dashboard'])->name('delivery.dashboard');
    Route::post('/delivery/orders/{orderId}/assign',         [DeliveryController::class, 'assignSelf'])->name('delivery.assign');
    Route::post('/delivery/orders/{orderId}/location',       [DeliveryController::class, 'updateLocation'])->name('delivery.location');
    Route::patch('/orders/{id}/status',                      [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
