<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::with('tickets')->latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $event,
            'message' => 'List Event'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_event'    => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tanggal_event' => 'required|date',
            'lokasi'        => 'required|string|max:255',
            'banner'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $event = new Event();
        $event->nama_event    = $request->nama_event;
        $event->deskripsi     = $request->deskripsi;
        $event->tanggal_event = $request->tanggal_event;
        $event->lokasi        = $request->lokasi;

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events', 'public');
            $event->banner = $path;
        }

        $event->save();

        return response()->json([
            'success' => true,
            'data'    => $event,
            'message' => 'Event berhasil dibuat'
        ], 201);
    }

    public function show($id)
    {
        $event = Event::with('tickets')->find($id);
        if (!$event) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $event,
            'message' => 'Detail Event'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_event'    => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tanggal_event' => 'required|date',
            'lokasi'        => 'required|string|max:255',
            'banner'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $event->nama_event    = $request->nama_event;
        $event->deskripsi     = $request->deskripsi;
        $event->tanggal_event = $request->tanggal_event;
        $event->lokasi        = $request->lokasi;

        if ($request->hasFile('banner')) {
            if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                Storage::disk('public')->delete($event->banner);
            }
            $path = $request->file('banner')->store('events', 'public');
            $event->banner = $path;
        }

        $event->save();

        return response()->json([
            'success' => true,
            'data'    => $event,
            'message' => 'Event berhasil diupdate'
        ], 200);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($event->banner && Storage::disk('public')->exists($event->banner)) {
            Storage::disk('public')->delete($event->banner);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'data'    => $event,
            'message' => 'Event berhasil dihapus'
        ], 200);
    }
}
