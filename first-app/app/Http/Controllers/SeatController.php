<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::latest()->with(['show'])->where(function ($i) {
            $search = request('search');

            if ($search) {
                return $i->where('seat_number', 'like', "%$search%")->orWhere('seat_status', 'like', "%$search%");
            }
        })->get();

        return response()->json([
            'seats' => $seats,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'show_id' => ['required'],
                'seat_number' => ['required'],
                'seat_status' => ['required'],
            ]);

            $seat = Seat::create($validated);

            return response()->json([
                'seat' => $seat,
            ]);
        } catch (\Exception $e) {
            // return response()->json([
            //     'message' => 'Data has already exist.'
            // ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $seat = Seat::find($id);

        $request->validate([
            'show_id' => ['required'],
            'seat_number' => ['required'],
            'seat_status' => ['required'],
        ]);

        $seat->update([
            'show_id' => $request->show_id,
            'seat_number' => $request->seat_number,
            'seat_status' => $request->seat_status,
        ]);

        return response()->json([
            'seat' => $seat,
        ]);
    }

    public function destroy($id)
    {
        Seat::destroy($id);

        return response()->json([
            'status' => 'Seat deleted',
        ]);
    }
}