<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();
        return view('account.order-detail', compact('order'));
    }
}
