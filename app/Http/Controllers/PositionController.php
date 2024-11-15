<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @group Position Management
 *
 * This controller handles all operations related to managing positions. It includes functionalities
 * for retrieving, creating, updating, and deleting positions. The operations are gated based on user
 * authorization and the user's authentication status.
 */
class PositionController extends Controller
{
    /**
     * Display a listing of the positions.
     *
     * This endpoint retrieves a list of positions based on user authentication status:
     * - Authenticated users: Receives paginated results of positions.
     * - Unauthenticated users: Receives a limited number of positions.
     *
     * @queryParam page int The page number for pagination (defaults to 1).
     *
     * @response 200 scenario="Successful response" {
     *    "success": true,
     *    "message": "Positions retrieved successfully",
     *    "data": [
     *        {
     *            "id": 1,
     *            "advertising_start_date": "2024-01-01",
     *            "advertising_end_date": "2024-01-31",
     *            "title": "Position Title",
     *            "description": "Position Description",
     *            "keywords": "keyword1, keyword2",
     *            "min_salary": 50000,
     *            "max_salary": 70000,
     *            "salary_currency": "AUD",
     *            "company_id": 1,
     *            "user_id": 1,
     *            "benefits": "Benefit details",
     *            "requirements": "Requirement details",
     *            "position_type": "permanent"
     *        }
     *    ]
     * }
     *
     * @response 401 scenario="Unauthorized" {
     *    "success": false,
     *    "message": "You must be logged in to view this position."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $positions = Position::Paginate(2);
        } else {
            $positions = Position::limit(1)->get();
        }

        return ApiResponseClass::sendResponse(
            $positions,
            "Positions retrieved successfully"
        );
    }

    /**
     * Store a newly created position in storage.
     *
     * This endpoint allows authorized users to create a new position. All fields marked as required must
     * be provided, and the user must be authorized to create positions.
     *
     * @bodyParam advertising_start_date string required The start date for advertising the position.
     * @bodyParam advertising_end_date string required The end date for advertising the position.
     * @bodyParam title string required The title of the position.
     * @bodyParam description string required The description of the position.
     * @bodyParam keywords string optional Keywords related to the position.
     * @bodyParam min_salary numeric optional The minimum salary for the position.
     * @bodyParam max_salary numeric optional The maximum salary for the position.
     * @bodyParam salary_currency string optional The currency of the salary. Defaults to AUD.
     * @bodyParam company_id int required The ID of the company offering the position.
     * @bodyParam user_id int required The ID of the user creating the position.
     * @bodyParam benefits string optional Benefits associated with the position.
     * @bodyParam requirements string optional Requirements for the position.
     * @bodyParam position_type string required The type of the position (permanent, contract, part-time, casual).
     *
     * @response 201 scenario="Position created successfully" {
     *    "success": true,
     *    "message": "Position created successfully",
     *    "data": {
     *        "id": 1,
     *        "advertising_start_date": "2024-01-01",
     *        "advertising_end_date": "2024-01-31",
     *        "title": "Position Title",
     *        "description": "Position Description",
     *        "keywords": "keyword1, keyword2",
     *        "min_salary": 50000,
     *        "max_salary": 70000,
     *        "salary_currency": "AUD",
     *        "company_id": 1,
     *        "user_id": 1,
     *        "benefits": "Benefit details",
     *        "requirements": "Requirement details",
     *        "position_type": "permanent"
     *    }
     * }
     *
     * @response 403 scenario="Unauthorized" {
     *    "success": false,
     *    "message": "This action is unauthorized."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Position::class);

        $validateData = $request->validate([
            'advertising_start_date' => 'required|date',
            'advertising_end_date' => 'required|date',
            'title' => 'required|string',
            'description' => 'required|string',
            'keywords' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'salary_currency' => 'nullable|string|in:AUD',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
            'benefits' => 'nullable|string',
            'requirements' => 'nullable|string',
            'position_type' => 'required|string|in:permanent,contract,part-time,casual',
        ]);

        $position = Position::create($validateData);

        return ApiResponseClass::sendResponse(
            $position,
            'Position created successfully',
            201
        );
    }

    /**
     * Display the specified position.
     *
     * This endpoint retrieves a specific position by its ID. Users must be authenticated to access this endpoint.
     *
     * @urlParam position int required The ID of the position to retrieve.
     *
     * @response 200 scenario="Successful response" {
     *    "success": true,
     *    "message": "Position retrieved successfully",
     *    "data": {
     *        "id": 1,
     *        "advertising_start_date": "2024-01-01",
     *        "advertising_end_date": "2024-01-31",
     *        "title": "Position Title",
     *        "description": "Position Description",
     *        "keywords": "keyword1, keyword2",
     *        "min_salary": 50000,
     *        "max_salary": 70000,
     *        "salary_currency": "AUD",
     *        "company_id": 1,
     *        "user_id": 1,
     *        "benefits": "Benefit details",
     *        "requirements": "Requirement details",
     *        "position_type": "permanent"
     *    }
     * }
     *
     * @response 401 scenario="Unauthorized" {
     *    "success": false,
     *    "message": "You must be logged in to view this position."
     * }
     * @response 404 scenario="Not found" {
     *    "success": false,
     *    "message": "Position not found"
     * }
     *
     * @param Request $request
     * @param Position $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Position $position)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to view this position.'
            ], 401);
        }

        Gate::authorize('view', $position);

        return ApiResponseClass::sendResponse(
            $position,
            "Position retrieved successfully"
        );
    }

    /**
     * Update the specified position in storage.
     *
     * This endpoint allows an authorized user to update the details of a specific position.
     *
     * @urlParam position int required The ID of the position to update.
     * @bodyParam advertising_start_date string required The updated start date for advertising the position.
     * @bodyParam advertising_end_date string required The updated end date for advertising the position.
     * @bodyParam title string required The updated title of the position.
     * @bodyParam description string required The updated description of the position.
     * @bodyParam keywords array optional Updated keywords related to the position.
     * @bodyParam min_salary numeric optional Updated minimum salary for the position.
     * @bodyParam max_salary numeric optional Updated maximum salary for the position.
     * @bodyParam salary_currency string optional Updated currency of the salary. Defaults to AUD.
     * @bodyParam company_id int required The ID of the company offering the position.
     * @bodyParam user_id int required The ID of the user creating the position.
     * @bodyParam benefits string optional Updated benefits associated with the position.
     * @bodyParam requirements string optional Updated requirements for the position.
     * @bodyParam position_type string required Updated type of the position (permanent, contract, part-time, casual).
     *
     * @response 200 scenario="Position updated successfully" {
     *    "success": true,
     *    "message": "Position updated successfully",
     *    "data": {
     *        "id": 1,
     *        "advertising_start_date": "2024-01-01",
     *        "advertising_end_date": "2024-01-31",
     *        "title": "Position Title",
     *        "description": "Position Description",
     *        "keywords": "keyword1, keyword2",
     *        "min_salary": 50000,
     *        "max_salary": 70000,
     *        "salary_currency": "AUD",
     *        "company_id": 1,
     *        "user_id": 1,
     *        "benefits": "Benefit details",
     *        "requirements": "Requirement details",
     *        "position_type": "permanent"
     *    }
     * }
     *
     * @response 403 scenario="Unauthorized" {
     *    "success": false,
     *    "message": "This action is unauthorized."
     * }
     * @response 404 scenario="Not found" {
     *    "success": false,
     *    "message": "Position not found"
     * }
     *
     * @param Request $request
     * @param Position $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Position $position)
    {
        Gate::authorize('update', $position);

        $validateData = $request->validate([
            'advertising_start_date' => 'required|date',
            'advertising_end_date' => 'required|date',
            'title' => 'required|string',
            'description' => 'required|string',
            'keywords' => 'nullable|string',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'salary_currency' => 'nullable|string|in:AUD',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
            'benefits' => 'nullable|string',
            'requirements' => 'nullable|string',
            'position_type' => 'required|string|in:permanent,contract,part-time,casual',
        ]);

        $position->update($validateData);

        return ApiResponseClass::sendResponse(
            $position,
            'Position updated successfully'
        );
    }

    /**
     * Remove the specified position from storage.
     *
     * This endpoint allows an authorized user to delete a specific position.
     *
     * @urlParam position int required The ID of the position to delete.
     *
     * @response 200 scenario="Position deleted successfully" {
     *    "success": true,
     *    "message": "Position deleted successfully"
     * }
     *
     * @response 403 scenario="Unauthorized" {
     *    "success": false,
     *    "message": "This action is unauthorized."
     * }
     * @response 404 scenario="Not found" {
     *    "success": false,
     *    "message": "Position not found"
     * }
     *
     * @param Position $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Position $position)
    {
        Gate::authorize('delete', $position);

        $position->delete();

        return ApiResponseClass::sendResponse(
            null,
            'Position deleted successfully'
        );
    }
}
