<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function process(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validasi data yang diterima
            $validated = $request->validate([
                'total' => 'required|numeric',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric',
            ]);

            $order = Order::create([
                "user_id" => auth()->user()->id,
                "total"=> $validated['total'],
                "status"=> "pending",
            ]);

            foreach ($validated['products'] as $product) {
                OrderDetail::create([
                    'product_id' => $product['id'],
                    'order_id' => $order->id,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'] * $product['quantity'],
                ]);

                // Kurangi stok produk
                Product::findOrFail($product['id'])->decrement('stock', $product['quantity']);
            }

            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $order->id,
                    'gross_amount' => $validated['total'],
                ),
                'customer_details' => array(
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snaps_token = $snapToken;
            $order->save();

            DB::commit();

            return response()->json(['success' => true, 'order_id' => $order->id], 200);
        } catch (\Exception $e) {
            // Jika ada error, rollback transaksi
            DB::rollBack();
            throw $e;
        }
    }

    public function payment($id)
    {
        $order = Order::findOrFail($id);
        return view('checkout.payment', compact('order'));
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return view('checkout.history', compact('orders'));
    }

    public function success($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'success']);
        return view('checkout.success');
    }
}
