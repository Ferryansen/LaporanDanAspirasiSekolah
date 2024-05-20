<?php

namespace App\Http\Controllers;

use App\Models\StaffType;
use App\Models\User;
use Illuminate\Http\Request;


class StaffTypeController extends Controller
{

    public function index()
    {
        $staffTypes = StaffType::paginate(10)->withQueryString();

        $data = [
            'staffTypes' => $staffTypes,
        ];

        return view('stafftype.admin.StaffTypeList', $data);
    }

    public function createStaffType(Request $request)
    {
        $request->validate([
            'staffTypeName' => 'required',
        ]);

        $staffType = StaffType::create([
            'name' => $request->staffTypeName,
        ]);

        return redirect()->route('admin.staffTypeList');
    }

    public function createStaffTypeForm(){
        return view('stafftype.admin.createStaffTypeForm');
    }

    public function updateStaffTypeForm($id){
        $staffType = StaffType::findorFail($id);
        return view('stafftype.admin.updateStaffTypeForm', compact('staffType'));
    }

    public function updateStaffType(Request $request, $id)
    {
        $request->validate([
            'staffTypeName' => 'required',
        ]);

        $staffType = StaffType::findOrFail($id);

        $staffType->update([
            'name' => $request->staffTypeName,
            
        ]);

        return redirect()->route('admin.staffTypeList');
    }

    public function deleteStaffType(Request $request){
        $staffType = StaffType::find($request->id);
    
        // Update users with a new staffType_id or set to null
        User::where('staffType_id', $request->id)->update(['staffType_id' => null]);
    
        $staffType->delete();
        
        return redirect()->route('admin.staffTypeList');
    }
}