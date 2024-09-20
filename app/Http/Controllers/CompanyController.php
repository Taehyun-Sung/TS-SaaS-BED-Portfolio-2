<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['success' => true, 'message' => 'Companies retrieved successfully', 'data' => Company::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        $company = Company::create($request->all());
        return response()->json(['success' => true, 'message' => 'Company created successfully', 'data' => $company], 201);
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Company retrieved successfully', 'data' => $company]);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return response()->json(['success' => true, 'message' => 'Company updated successfully', 'data' => $company]);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Company deleted successfully']);
    }
}
