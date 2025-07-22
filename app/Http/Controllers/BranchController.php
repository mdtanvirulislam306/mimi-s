<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BranchModel;

class BranchController extends Controller
{
    //index method to show all branches
    public function index()
    {
        $branches = BranchModel::paginate(10);
        return view('backend.setup_configurations.branch.index', compact('branches'));  
    }
    //create method to show the form for creating a new branch
    public function create()
    {
        return view('backend.setup_configurations.branch.create');
    }
    //store method to save a new branch
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:branch,name',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]); 
        BranchModel::create($request->all());
        flash(translate('Branch has been created successfully.'))->success();
        return redirect()->route('branch.index');
    }
    //edit method to show the form for editing an existing branch
    public function edit($id)
    {
        $branch = BranchModel::findOrFail($id);
        return view('backend.setup_configurations.branch.edit', compact('branch'));
    }
    //update method to update an existing branch
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:branch,name,'.$id,
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);
        $branch = BranchModel::findOrFail($id);
        $branch->update($request->all());
        flash(translate('Branch has been updated successfully.'))->success();
        return redirect()->route('branch.index');
    }
    //destroy method to delete an existing branch
    public function destroy($id)
    {
        $branch = BranchModel::findOrFail($id);
        $branch->delete();
        flash(translate('Branch has been deleted successfully.'))->success();
        return redirect()->route('branch.index');
    }
}
