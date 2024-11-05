<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                "total" => "numeric|required"
            ]);
            $total = $request->total;

            $order = Order::create([
                "total"=> $total,
                "status"=> "pending",
            ]);

            foreach ($request->products as $product) {
                OrderDetail::create([
                    "product_id"=> $product->id,
                    "order_id"=> $order->id,
                    "quantity"=> $product->quantity,
                    "price"=> $product->price * $product->quantity,
                ]);
            }

            return redirect('/pembayaran')->with('success','Order Berhasil Silahkan Bayar');
        } catch (\Exception $e) {
            // Jika ada error, rollback transaksi
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        $orders = Order::findOrFail($id);
        $order_details = OrderDetail::where('order_id', $id)->get();
        return view('order.show', compact('orders', 'order_details'));
    }
}
