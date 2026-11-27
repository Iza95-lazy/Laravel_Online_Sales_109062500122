<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // Atau Illuminate\Http\Resources\Json\AnonymousResourceCollection jika pakai API Resource

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        // Ambil pesanan milik user yang sedang login
        $orders = $request->user()->orders()->latest('ordered_at')->paginate(10);

        // Kembalikan response JSON (bisa dibungkus dengan API Resource jika ada)
        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        // Cek kepemilikan order (Keamanan M8.5)
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden. Ini bukan pesanan Anda.'], 403);
        }

        // Load detail item pesanan
        $order->load('items');

        return response()->json($order);
    }
}