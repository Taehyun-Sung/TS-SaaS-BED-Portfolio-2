<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    /**
     * Get all companies.
     *
     * This endpoint retrieves all the companies from the database.
     *
     * @response 200 scenario="Successful response" {
     *    "status": "success",
     *    "data": [
     *        {
     *            "id": 1,
     *            "name": "Company A",
     *            "city_id": 1,
     *            "state_id": 1,
     *            "country_id": 1,
     *            "logo": null
     *        }
     *    ],
     *    "message": "Companies retrieved successfully"
     * }
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Company::class);
        $company = Company::all();
        return ApiResponseClass::sendResponse(
            $company,
            "Companies retrieved successfully"
        );

    }

    /**
     * Get a single company by ID.
     *
     * This endpoint retrieves the details of a specific company using its ID.
     *
     * @urlParam company int required The ID of the company to retrieve.
     * @response 200 scenario="Successful response" {
     *    "status": "success",
     *    "data": {
     *        "id": 1,
     *        "name": "Company A",
     *        "city_id": 1,
     *        "state_id": 1,
     *        "country_id": 1,
     *        "logo": null
     *    },
     *    "message": "Company retrieved successfully"
     * }
     */
    public function show(Company $company)
    {
        return ApiResponseClass::sendResponse(
            $company,
            "Company retrieved successfully"
        );
    }

    /**
     * Create a new company.
     *
     * This endpoint allows authorized users to create a new company in the database.
     *
     * @bodyParam name string required The name of the company.
     * @bodyParam city_id int required The ID of the city where the company is located.
     * @bodyParam state_id int required The ID of the state where the company is located.
     * @bodyParam country_id int required The ID of the country where the company is located.
     * @bodyParam logo file Optional logo of the company.
     * @response 201 scenario="Company created successfully" {
     *    "status": "success",
     *    "data": {
     *        "id": 2,
     *        "name": "Company B",
     *        "city_id": 2,
     *        "state_id": 2,
     *        "country_id": 2,
     *        "logo": null
     *    },
     *    "message": "Company created successfully"
     * }
     */
    public function store(Request $request) {
        Gate::authorize('create', Company::class);

        $validateData = $request->validate([
            'name' => 'required|string',
            'city_id' => 'required|integer',
            'state_id' => 'required|integer',
            'country_id' => 'required|integer',
            'logo' => 'nullable',
        ]);
        $validateData['user_id'] = auth()->id();

        try {
            $company = Company::create($validateData);
            return ApiResponseClass::sendResponse(
                $company,
                'Company created successfully',
                201
            );
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'A company with these details already exists.'
                ], 400);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing your request.'
        ], 500);
    }

    /**
     * Update a company by ID.
     *
     * This endpoint allows an authorized user to update the details of an existing company.
     *
     * @urlParam company int required The ID of the company to update.
     * @bodyParam name string required The updated name of the company.
     * @bodyParam city_id int required The updated city ID.
     * @bodyParam state_id int required The updated state ID.
     * @bodyParam country_id int required The updated country ID.
     * @bodyParam logo file Optional updated company logo.
     * @response 200 scenario="Successful update" {
     *    "status": "success",
     *    "data": {
     *        "id": 1,
     *        "name": "Updated Company",
     *        "city_id": 1,
     *        "state_id": 1,
     *        "country_id": 1,
     *        "logo": null
     *    },
     *    "message": "Company updated successfully"
     * }
     */
    public function update(Request $request, Company $company)
    {
        Gate::authorize('update', $company);

        $validateData = $request->validate([
            'name' => 'required|string',
            'city_id' => 'required|integer',
            'state_id' => 'required|integer',
            'country_id' => 'required|integer',
            'logo' => 'nullable'
        ]);

        $company->update($request->only(['name', 'city_id', 'state_id', 'country_id', 'logo']));


        $company->update($validateData);
        return ApiResponseClass::sendResponse(
            $company,
            'Company updated successfully',
            200
        );
    }

    /**
     * Delete a company by ID.
     *
     * This endpoint allows an authorized user to delete a company by its ID.
     *
     * @urlParam company int required The ID of the company to delete.
     * @response 200 scenario="Successful deletion" {
     *    "status": "success",
     *    "message": "Company deleted successfully"
     * }
     */
    public function destroy(Company $company)
    {
        Gate::authorize('delete', $company);
        $company->delete();
        return ApiResponseClass::sendResponse(
            $company,
            'Company deleted successfully',
            200
        );
    }

    /**
     * Permanently delete all soft-deleted companies.
     *
     * This endpoint permanently removes all companies that have been soft-deleted.
     *
     * @response 200 scenario="All companies deleted" {
     *    "message": "All companies permanently deleted successfully"
     * }
     */
    public function destroyAll()
    {
        Gate::authorize('forceDeleteAll', Company::class);

        $companies = Company::onlyTrashed()->get();
        foreach ($companies as $company) {
            $company->forceDelete();
        }

        return response()->json(['message' => 'All companies permanently deleted successfully']);
    }

    /**
     * Restore a deleted company by ID.
     *
     * This endpoint allows an authorized user to restore a company that was soft-deleted.
     *
     * @urlParam id int required The ID of the company to restore.
     * @response 200 scenario="Company restored" {
     *    "status": "success",
     *    "message": "Company restored successfully"
     * }
     */
    public function restore($id)
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        Gate::authorize('restore', $company);
        $company->restore();
        return ApiResponseClass::sendResponse(
            $company,
            'Company restored successfully',
            200
        );
    }

    /**
     * Restore all soft-deleted companies.
     *
     * This endpoint allows an authorized user to restore all soft-deleted companies.
     *
     * @response 200 scenario="All companies restored" {
     *    "message": "All companies restored successfully"
     * }
     */
    public function restoreAll()
    {
        Gate::authorize('restoreAll', Company::class);

        $companies = Company::onlyTrashed()->get();
        foreach ($companies as $company) {
            $company->restore();
        }

        return response()->json(['message' => 'All companies restored successfully']);
    }
}
