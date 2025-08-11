<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::with('orderItems.ticket.event')->latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'List Order'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemesan'  => 'required|string|max:255',
            'email'         => 'required|email',
            'total_harga'   => 'required|numeric|min:0',
            'status'        => 'required|string|in:pending,paid,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Order::create($request->all());

        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order berhasil dibuat'
        ], 201);
    }

    public function show($id)
    {
        $order = Order::with('orderItems.ticket.event')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Detail Order'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemesan'  => 'required|string|max:255',
            'email'         => 'required|email',
            'total_harga'   => 'required|numeric|min:0',
            'status'        => 'required|string|in:pending,paid,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $order->update($request->all());

        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order berhasil diupdate'
        ], 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order berhasil dihapus'
        ], 200);
    }
}
