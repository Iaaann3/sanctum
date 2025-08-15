<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index()
    {
        $ticket = Ticket::with('event')->latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $ticket,
            'message' => 'List Ticket',
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'   => 'required|exists:events,id',
            'nama_tiket' => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ticket = Ticket::create($request->all());

        return response()->json([
            'success' => true,
            'data'    => $ticket,
            'message' => 'Ticket berhasil dibuat',
        ], 201);
    }

    public function show($id)
    {
        $ticket = Ticket::with('event')->find($id);
        if (! $ticket) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $ticket,
            'message' => 'Detail Ticket',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'event_id'   => 'required|exists:events,id',
            'nama_tiket' => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ticket = Ticket::find($id);
        if (! $ticket) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $ticket->update($request->all());

        return response()->json([
            'success' => true,
            'data'    => $ticket,
            'message' => 'Ticket berhasil diupdate',
        ], 200);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if (! $ticket) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $ticket->delete();

        return response()->json([
            'success' => true,
            'data'    => $ticket,
            'message' => 'Ticket berhasil dihapus',
        ], 200);
    }
    // API: PUT /api/tickets/{id}/update-stok
    public function updateStok(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (! $ticket) {
            return response()->json(['message' => 'Ticket Not Found'], 404);
        }

        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $ticket->stok = $request->stok;
        $ticket->save();

        return response()->json([
            'success' => true,
            'data'    => $ticket,
            'message' => 'Stok tiket berhasil diupdate',
        ], 200);
    }

}
