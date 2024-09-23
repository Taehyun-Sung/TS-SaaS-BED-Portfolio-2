<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     * ## Get Companies
     * - **Endpoint**: `GET /companies`
     * - **Description**: Retrieves all companies.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Companies retrieved successfully",
     *   "data": [ ... ]  // Array of companies
     * }
     * ```
     */
    public function index()
    {
        return response()->json(['success' => true, 'message' => 'Companies retrieved successfully', 'data' => Company::all()]);
    }

    /**
     * Store a newly created company.
     *
     * ## Create Company
     * - **Endpoint**: `POST /companies`
     * - **Description**: Creates a new company.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "name": "Company Name"
     * }
     * ```
     *
     * **Successful Response (201)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Company created successfully",
     *   "data": { ... }  // Company details
     * }
     * ```
     *
     * **Error Response (422)**:
     * ```json
     * {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "data": {
     *     "name": ["The name field is required."]
     *   }
     * }
     * ```
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        $company = Company::create($request->all());
        return response()->json(['success' => true, 'message' => 'Company created successfully', 'data' => $company], 201);
    }

    /**
     * Display the specified company.
     *
     * ## Get Company
     * - **Endpoint**: `GET /companies/{id}`
     * - **Description**: Retrieves a company by ID.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Company retrieved successfully",
     *   "data": { ... }  // Company details
     * }
     * ```
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Company retrieved successfully', 'data' => $company]);
    }

    /**
     * Update the specified company.
     *
     * ## Update Company
     * - **Endpoint**: `PUT /companies/{id}`
     * - **Description**: Updates a company by ID.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "name": "Updated Company Name"
     * }
     * ```
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Company updated successfully",
     *   "data": { ... }  // Updated company details
     * }
     * ```
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return response()->json(['success' => true, 'message' => 'Company updated successfully', 'data' => $company]);
    }

    /**
     * Remove the specified company.
     *
     * ## Delete Company
     * - **Endpoint**: `DELETE /companies/{id}`
     * - **Description**: Deletes a company by ID.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Company deleted successfully"
     * }
     * ```
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Company deleted successfully']);
    }
}
