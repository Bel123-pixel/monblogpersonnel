<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sellerIndex()
    {
        // Au lieu de passer par l'utilisateur, on cherche directement dans la table Order
        // via l'ID de l'utilisateur connecté
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        
        return view('orders.my', compact('orders'));
    }

    public function myOrders()
    {
        return $this->sellerIndex();
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Action non autorisée.');
        }

        return view('orders.show', compact('order'));
    }
}