<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::oldest()->where(function ($i) {
            $search = request('search');

            if ($search) {
                return $i->where('studio', 'like', "%$search%");
            }
        })->get();

        return response()->json([
            'studios' => $studios,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'studio' => ['required'],
            ]);

            $studio = Studio::create($validated);

            return response()->json([
                'studio' => $studio,
            ]);
        } catch (\Exception $e) {
            // return response()->json([
            //     'message' => 'Data has already exist.'
            // ], 422);
        }
    }

    public function destroy($id)
    {
        Studio::destroy($id);

        return response()->json([
            'status' => 'Studio deleted',
        ]);
    }
}