<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItem = OrderItem::with('order', 'ticket.event')->latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $orderItem,
            'message' => 'List Order Item'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'  => 'required|exists:orders,id',
            'ticket_id' => 'required|exists:tickets,id',
            'jumlah'    => 'required|integer|min:1',
            'subtotal'  => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $orderItem = OrderItem::create($request->all());

        return response()->json([
            'success' => true,
            'data'    => $orderItem,
            'message' => 'Order Item berhasil dibuat'
        ], 201);
    }

    public function show($id)
    {
        $orderItem = OrderItem::with('order', 'ticket.event')->find($id);
        if (!$orderItem) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $orderItem,
            'message' => 'Detail Order Item'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order_id'  => 'required|exists:orders,id',
            'ticket_id' => 'required|exists:tickets,id',
            'jumlah'    => 'required|integer|min:1',
            'subtotal'  => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $orderItem = OrderItem::find($id);
        if (!$orderItem) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $orderItem->update($request->all());

        return response()->json([
            'success' => true,
            'data'    => $orderItem,
            'message' => 'Order Item berhasil diupdate'
        ], 200);
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::find($id);
        if (!$orderItem) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $orderItem->delete();

        return response()->json([
            'success' => true,
            'data'    => $orderItem,
            'message' => 'Order Item berhasil dihapus'
        ], 200);
    }
}
