<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DeliveryDashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('delivery.dashboard');
    }
}
