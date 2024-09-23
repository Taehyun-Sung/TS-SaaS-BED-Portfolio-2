<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the positions.
     *
     * ## Get Positions
     * - **Endpoint**: `GET /positions`
     * - **Description**: Retrieves all positions.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Positions retrieved successfully",
     *   "data": [ ... ]  // Array of positions
     * }
     * ```
     */
    public function index()
    {
        return response()->json(['success' => true, 'message' => 'Positions retrieved successfully', 'data' => Position::all()]);
    }

    /**
     * Store a newly created position.
     *
     * ## Create Position
     * - **Endpoint**: `POST /positions`
     * - **Description**: Creates a new position.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "title": "Position Title"
     * }
     * ```
     *
     * **Successful Response (201)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Position created successfully",
     *   "data": { ... }  // Position details
     * }
     * ```
     *
     * **Error Response (422)**:
     * ```json
     * {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "data": {
     *     "title": ["The title field is required."]
     *   }
     * }
     * ```
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        $position = Position::create($request->all());
        return response()->json(['success' => true, 'message' => 'Position created successfully', 'data' => $position], 201);
    }

    /**
     * Display the specified position.
     *
     * ## Get Position
     * - **Endpoint**: `GET /positions/{id}`
     * - **Description**: Retrieves a position by ID.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Position retrieved successfully",
     *   "data": { ... }  // Position details
     * }
     * ```
     */
    public function show($id)
    {
        $position = Position::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Position retrieved successfully', 'data' => $position]);
    }

    /**
     * Update the specified position.
     *
     * ## Update Position
     * - **Endpoint**: `PUT /positions/{id}`
     * - **Description**: Updates a position by ID.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "title": "Updated Position Title"
     * }
     * ```
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Position updated successfully",
     *   "data": { ... }  // Updated position details
     * }
     * ```
     */
    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);
        $position->update($request->all());
        return response()->json(['success' => true, 'message' => 'Position updated successfully', 'data' => $position]);
    }

    /**
     * Remove the specified position.
     *
     * ## Delete Position
     * - **Endpoint**: `DELETE /positions/{id}`
     * - **Description**: Deletes a position by ID.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Position deleted successfully"
     * }
     * ```
     */
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();
        return response()->json(['success' => true, 'message' => 'Position deleted successfully']);
    }
}
