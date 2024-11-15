<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PositionController extends Controller
{
    /**
     * Display a listing of the positions.
     *
     * This endpoint retrieves a list of positions. For authenticated users, it returns paginated results.
     * For unauthenticated users, it returns a limited number of positions.
     *
     * @response 200 scenario="Successful response" {
     *    "status": "success",
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
     *    ],
     *    "message": "Positions retrieved successfully"
     * }
     * @response 401 scenario="Unauthorized" {"success": false, "message": "You must be logged in to view this position."}
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
     * This endpoint allows authorized users to create a new position.
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
     * @response 201 scenario="Position created successfully" {
     *    "status": "success",
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
     *    },
     *    "message": "Position created successfully"
     * }
     * @response 403 scenario="Unauthorized" {"success": false, "message": "This action is unauthorized."}
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

//        $position = $request->user()->positions()->create($validateData);
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
     * @response 200 scenario="Successful response" {
     *    "status": "success",
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
     *    },
     *    "message": "Position retrieved successfully"
     * }
     * @response 401 scenario="Unauthorized" {"success": false, "message": "You must be logged in to view this position."}
     * @response 404 scenario="Not found" {"success": false, "message": "Position not found"}
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
     * @response 200 scenario="Successful update" {
     *    "status": "success",
     *    "data": {
     *        "id": 1,
     *        "advertising_start_date": "2024-01-01",
     *        "advertising_end_date": "2024-01-31",
     *        "title": "Updated Position Title",
     *        "description": "Updated Position Description",
     *        "keywords": "updated_keyword1, updated_keyword2",
     *        "min_salary": 60000,
     *        "max_salary": 80000,
     *        "salary_currency": "AUD",
     *        "company_id": 1,
     *        "user_id": 1,
     *        "benefits": "Updated benefit details",
     *        "requirements": "Updated requirement details",
     *        "position_type": "contract"
     *    },
     *    "message": "Position updated successfully"
     * }
     * @response 403 scenario="Unauthorized" {"success": false, "message": "This action is unauthorized."}
     * @response 404 scenario="Not found" {"success": false, "message": "Position not found"}
     */
    public function update(Request $request, Position $position)
    {
        Gate::authorize('update', $position);

        $validateData = $request->validate([
            'advertising_start_date' => 'required|date',
            'advertising_end_date' => 'required|date',
            'title' => 'required|string',
            'description' => 'required|string',
            'keywords' => 'nullable|array',
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
            'Position updated successfully',
            200
        );
    }

    /**
     * Remove the specified position from storage.
     *
     * This endpoint allows an authorized user to delete a specific position. The position is soft deleted.
     *
     * @urlParam position int required The ID of the position to delete.
     * @response 200 scenario="Successful deletion" {
     *    "status": "success",
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
     *    },
     *    "message": "Position deleted successfully"
     * }
     * @response 403 scenario="Unauthorized" {"success": false, "message": "This action is unauthorized."}
     * @response 404 scenario="Not found" {"success": false, "message": "Position not found"}
     */
    public function destroy(Position $position)
    {
        Gate::authorize('delete', $position);
        $position->delete();
        return ApiResponseClass::sendResponse(
            $position,
            'Position deleted successfully',
            200
        );
    }

    /**
     * Permanently delete all soft-deleted positions.
     *
     * This endpoint allows an authorized user to permanently delete all positions that have been soft deleted.
     *
     * @response 200 scenario="Successful deletion" {"message": "All positions permanently deleted successfully"}
     * @response 403 scenario="Unauthorized" {"success": false, "message": "This action is unauthorized."}
     */
    public function destroyAll()
    {
        Gate::authorize('forceDeleteAll', Position::class);

        $positions = Position::onlyTrashed()->get();
        foreach ($positions as $position) {
            $position->forceDelete();
        }

        return response()->json(['message' => 'All positions permanently deleted successfully']);
    }

    /**
     * Restore a soft-deleted position.
     *
     * This endpoint allows an authorized user to restore a position that has been soft deleted.
     *
     * @urlParam id int required The ID of the position to restore.
     * @response 200 scenario="Successful restoration" {
     *    "status": "success",
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
     *    },
     *    "message": "Position restored successfully"
     * }
     * @response 404 scenario="Not found" {"success": false, "message": "Position not found"}
     */

    public function restore($id) {
        $company = Position::onlyTrashed()->findOrFail($id);
        $company->restore();
        return ApiResponseClass::sendResponse(
            $company,
            'Position restored successfully',
            200
        );
    }

    /**
     * Restore all soft-deleted positions.
     *
     * This endpoint allows an authorized user to restore all positions that have been soft deleted.
     *
     * @response 200 scenario="Successful restoration" {"message": "All positions restored successfully"}
     * @response 403 scenario="Unauthorized" {"success": false, "message": "This action is unauthorized."}
     */
    public function restoreAll()
    {
        Gate::authorize('restoreAll', Position::class);

        $positions = Company::onlyTrashed()->get();
        foreach ($positions as $position) {
            $position->restore();
        }

        return response()->json(['message' => 'All positions restored successfully']);
    }

}
