<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function company()
    {
        $companies = Company::latest()->paginate(15);
        return view('company.index', [
            'companies' => $companies
        ]);
    }


    public function storeCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:companies,name',
        ]);

        $slug = Str::slug($request->name);

        Company::create([
            'name' => $validated['name'],
            'created_by' => Auth::id(),
            'slug' => $slug,
        ]);
        
        return response()->json(['message' => 'Company Created Successfully']);
    }



    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $slug = Str::slug($validated['name']);
        $company = Company::create([
            'name' => $validated['name'],
            'created_by' => Auth::id(),
            'slug' => $slug,
        ]);

        return back()->with('success', 'Company Created Successfully');
    }

    public function updateCompany(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $company = Company::findOrFail($id);

        $company->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json([
            'message' => 'Company updated successfully.',
            'company' => $company
        ]);
    }

    public function deleteCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return back()->with('success', 'Company Deleted Successfully');
    }
}
