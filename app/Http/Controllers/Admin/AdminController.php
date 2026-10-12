<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /** GET /admin/dashboard */
    public function dashboard(): View
    {
        $stats = [
            'total_users'    => User::count(),
            'pending_users'  => User::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_orders'   => Order::count(),
            'active_orders'  => Order::whereIn('status', ['confirmed', 'preparing', 'in_transit'])->count(),
            'revenue'        => Order::where('status', 'delivered')->sum('total_amount'),
        ];

        $recentOrders = Order::with(['consumer', 'items'])
            ->latest()
            ->take(8)
            ->get();

        $pendingUsers = User::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'pendingUsers'));
    }

    /** GET /admin/users */
    public function users(Request $request): View
    {
        $query = User::withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $status);
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /** POST /admin/users/{id}/approve */
    public function approveUser(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', "{$user->name} has been approved.");
    }

    /** POST /admin/users/{id}/suspend */
    public function suspendUser(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $newStatus = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->update(['status' => $newStatus]);

        $msg = $newStatus === 'suspended' ? "{$user->name} has been suspended." : "{$user->name} has been unsuspended.";

        return back()->with('success', $msg);
    }

    /** DELETE /admin/users/{id} */
    public function deleteUser(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete an admin account.');
        }

        $user->delete(); // soft delete

        return back()->with('success', "{$user->name} has been deleted.");
    }

    /** POST /admin/users/{id}/role */
    public function changeUserRole(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent changing admin role
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot change an admin account\'s role.');
        }

        $request->validate([
            'role' => ['required', 'in:consumer,producer,delivery,admin'],
        ]);

        $newRole = $request->input('role');
        $oldRole = $user->role;

        $user->update(['role' => $newRole]);

        return back()->with('success', "{$user->name}'s role has been changed from " . ucfirst($oldRole) . " to " . ucfirst($newRole) . ".");
    }

    /** GET /admin/products */
    public function products(Request $request): View
    {
        $query = Product::with('producer')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /** GET /admin/reviews */
    public function reviews(Request $request): View
    {
        $query = Review::with(['consumer', 'product']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('consumer', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /** GET /admin/questions */
    public function questions(Request $request): View
    {
        $query = Question::with(['consumer', 'product', 'producer']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('consumer', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhere('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $questions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.questions.index', compact('questions'));
    }

    /** POST /admin/questions/{id}/answer */
    public function answerQuestion(Request $request, int $id): RedirectResponse
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'answer' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'answer.required' => 'Please provide an answer.',
            'answer.min' => 'Answer must be at least 5 characters.',
            'answer.max' => 'Answer cannot exceed 2000 characters.',
        ]);

        $question->update([
            'answer'      => $request->input('answer'),
            'producer_id' => auth()->id(),
        ]);

        return back()->with('success', 'Answer posted! The consumer will be notified.');
    }

    /** GET /admin/orders */
    public function orders(Request $request): View
    {
        $query = Order::with(['consumer', 'deliveryAgent', 'items']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('consumer', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(20, ['*'], 'page', 1)->withQueryString();
        $orders->setPageName('page');

        return view('admin.orders.index', compact('orders'));
    }

    /** GET /admin/statistics */
    public function statistics(): View
    {
        // Monthly revenue + order count for last 12 months
        $monthlyRevenue = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue, COUNT(*) as count")
            ->where('status', 'delivered')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->get();

        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Users by role
        $usersByRole = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        // Top 10 products by revenue
        $topProducts = OrderItem::selectRaw('product_id, SUM(quantity_kg) as total_kg, SUM(subtotal) as total_revenue')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        return view('admin.statistics', compact(
            'monthlyRevenue',
            'ordersByStatus',
            'usersByRole',
            'topProducts'
        ));
    }

    /** GET /admin/withdrawals */
    public function withdrawals(Request $request): View
    {
        $query = Withdrawal::with('producer');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(20)->withQueryString();

        $pendingCount = Withdrawal::where('status', 'pending')->count();

        return view('admin.withdrawals.index', compact('withdrawals', 'pendingCount'));
    }

    /** POST /admin/withdrawals/{id}/approve */
    public function approveWithdrawal(int $id): RedirectResponse
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status'       => 'approved',
            'processed_at' => now(),
            'admin_note'   => request('admin_note'),
        ]);

        return back()->with('success', "Withdrawal of KES " . number_format((float)$withdrawal->amount, 2) . " approved.");
    }

    /** POST /admin/withdrawals/{id}/reject */
    public function rejectWithdrawal(int $id): RedirectResponse
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status'       => 'rejected',
            'processed_at' => now(),
            'admin_note'   => request('admin_note'),
        ]);

        return back()->with('success', "Withdrawal rejected.");
    }
}
