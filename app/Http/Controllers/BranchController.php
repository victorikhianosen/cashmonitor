<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('company')->latest()->paginate(15);
        $companies = Company::all();

        return view('branch.index', compact('branches', 'companies'));
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|unique:branches,name',
        'company_id' => 'required|exists:companies,id',
        'account_number' => 'required|numeric|unique:branches,account_number',
    ]);

    $branch = Branch::create([
        'name' => $validated['name'],
        'company_id' => $validated['company_id'],
        'account_number' => $validated['account_number'],
        'slug' => Str::slug($validated['name']),
        'created_by' => auth()->id(),
    ]);

    return response()->json(['message' => 'Branch added successfully.'], 201);
}


 public function update(Request $request, $id)
{
    $branch = Branch::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|unique:branches,name,' . $id,
        'company_id' => 'required|exists:companies,id',
        'account_number' => 'required|numeric|unique:branches,account_number,' . $id,
    ]);

    $branch->update([
        'name' => $validated['name'],
        'company_id' => $validated['company_id'],
        'account_number' => $validated['account_number'],
        'slug' => Str::slug($validated['name']),
    ]);

    return response()->json(['message' => 'Branch updated successfully.']);
}



    public function delete($id)
    {
        $company = Branch::findOrFail($id);
        $company->delete();
        return back()->with('success', 'Branch Deleted Successfully');
    }
}
