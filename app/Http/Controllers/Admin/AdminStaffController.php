<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Admin\Staff;


class AdminStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validatedData = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'staff_id' => 'required|string|max:255|unique:staff', 
            'staff_firstname' => 'required|string|max:255',
            'staff_middlename' => 'required|string|max:255',
            'staff_lastname' => 'required|string|max:255',
            'staff_rfid' => 'required|string|max:255',
            'access_type' => 'required|string|max:255',

        ]);

        Staff::create($validatedData);

        return redirect()->route('admin.staff.index')
                        ->with('success', 'Staff created successfully.');
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
         $validatedData = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'staff_id' => 'required|string|max:255|unique:staff,staff_id,' . $staff->id,
            'staff_firstname' => 'required|string|max:255',
            'staff_middlename' => 'required|string|max:255',
            'staff_lastname' => 'required|string|max:255',
            'staff_rfid' => 'required|string|max:255|unique:staff',
            'access_type' => 'required|string|max:255',

        ]);

        // Check for changes
        $changesDetected = false;
        foreach ($validatedData as $key => $value) {
            if ($staff->$key !== $value) {
                $changesDetected = true;
                break;
            }
        }

        if (!$changesDetected) {
            return redirect()->route('admin.staff.index')->with('info', 'No changes were made.');
        }

        // Update the staff record
        $staff->update($validatedData);

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully.');
    }


    public function deleteAll(Request $request)
    {
        $count = Staff::count();

        if ($count === 0) {
            return redirect()->route('admin.staff.index')->with('info', 'There are no staff/s to delete.');
        }
        else{
            
            Staff::truncate();
            return redirect()->route('admin.staff.index')->with('success', 'All staff/s deleted successfully.');
        }

        
    }

}
