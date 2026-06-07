<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $orders = Order::with(['user', 'post'])->latest()->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    public function confirm(Order $order)
    {
        $statuses = ['pending' => 'confirmed', 'confirmed' => 'shipped', 'shipped' => 'delivered'];
        $order->update(['status' => $statuses[$order->status] ?? 'delivered']);
        return back()->with('success', 'Statut de la commande mis à jour.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Commande supprimée.');
    }
}
