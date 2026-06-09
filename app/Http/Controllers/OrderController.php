<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Post;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        Order::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'message' => $request->input('message'),
            'status'  => 'pending',
        ]);

        return back()->with('success', '✅ Votre commande a bien été envoyée ! HOUNTY vous contactera bientôt.');
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