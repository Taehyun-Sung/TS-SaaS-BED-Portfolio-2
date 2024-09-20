<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['success' => true, 'message' => 'Positions retrieved successfully', 'data' => Position::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        $position = Position::create($request->all());
        return response()->json(['success' => true, 'message' => 'Position created successfully', 'data' => $position], 201);
    }

    public function show($id)
    {
        $position = Position::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Position retrieved successfully', 'data' => $position]);
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);
        $position->update($request->all());
        return response()->json(['success' => true, 'message' => 'Position updated successfully', 'data' => $position]);
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();
        return response()->json(['success' => true, 'message' => 'Position deleted successfully']);
    }
}
