<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RankController extends Controller
{
    public function index()
    {
        $ranks = Rank::all();
        return response()->json($ranks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $rank = Rank::create([
            'name' => $validatedData['name'],
            'code' => Str::slug($validatedData['name']) . '-' . time()
        ]);

        return response()->json($rank, 201);
    }

    public function show(Rank $rank)
    {
        return response()->json($rank);
    }

    public function update(Request $request, Rank $rank)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $rank->update([
            'name' => $validatedData['name'],
            'code' => Str::slug($validatedData['name']) . '-' . time()
        ]);

        return response()->json($rank);
    }

    public function destroy(Rank $rank)
    {
        $rank->delete();
        return response()->json(null, 204);
    }
}