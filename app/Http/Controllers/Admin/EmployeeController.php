<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use \App\Models\Admin\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;




class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
        {
            // Validate input data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
                'department_id' => [
                    'required',
                    'exists:departments,id',
                ],
                'employee_id' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'employee_firstname' => 'required|string|max:255',
                'employee_middlename' => 'required|string|max:255',
                'employee_lastname' => 'required|string|max:255',
                'employee_rfid' => 'required|string|max:255',
                'employee_photo' => 'image|max:2048', // Example: validation for image upload
            ]);

            // Handle file upload if 'employee_photo' is present
            if ($request->hasFile('employee_photo')) {
                $fileNameWithExt = $request->file('employee_photo')->getClientOriginalName();
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('employee_photo')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('employee_photo')->storeAs('public/employee_photo', $fileNameToStore);
            } else {
                $fileNameToStore = 'user.png'; // Default file if no photo is uploaded
            }

            // Check if an employee with the same employee_id or employee_rfid already exists
            $existingEmployeeById = Employee::where('employee_id', $request->input('employee_id'))->first();
            $existingEmployeeByRfid = Employee::where('employee_rfid', $request->input('employee_rfid'))->first();

            if (!$existingEmployeeById && !$existingEmployeeByRfid) {
                $employee = new Employee();
                $employee->school_id = $request->input('school_id');
                $employee->department_id = $request->input('department_id');
                $employee->employee_id = $request->input('employee_id');
                $employee->employee_firstname = $request->input('employee_firstname');
                $employee->employee_middlename = $request->input('employee_middlename');
                $employee->employee_rfid = $request->input('employee_rfid');
                $employee->employee_lastname = $request->input('employee_lastname');
                $employee->employee_photo = $fileNameToStore;
                $employee->save();

                // return redirect()->route('admin.employee.index')
                //     ->with('success', 'Employee created successfully.');

                if (Auth::user()->hasRole('admin')) {
                    return redirect()->route('admin.employee.index')
                    ->with('success', 'Employee created successfully.');

                } else {
                    return redirect()->route('admin_staff.employee.index')
                    ->with('success', 'Employee created successfully.');
                }

            } else {
                $errorMessage = '';
                if ($existingEmployeeById) {
                    $employeeName = $existingEmployeeById->employee_firstname . ' ' . $existingEmployeeById->employee_lastname;
                    $errorMessage .= 'Employee ID ' . $request->input('employee_id') . ' is already taken by ' . $employeeName . '. ';
                }
                if ($existingEmployeeByRfid) {
                    $employeeName = $existingEmployeeByRfid->employee_firstname . ' ' . $existingEmployeeByRfid->employee_lastname;
                    $errorMessage .= 'Employee RFID No. ' . $request->input('employee_rfid') . ' is already taken by ' . $employeeName . '. ';
                }


                if (Auth::user()->hasRole('admin')) {

                    return redirect()->route('admin.employee.index')
                    ->with('error', $errorMessage . 'Try again.');

                } else {

                    return redirect()->route('admin_staff.employee.index')
                    ->with('error', $errorMessage . 'Try again.');
                }
                
            }
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
   
     public function update(Request $request, $id)
    {
        // Validate input data
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'department_id' => [
                'required',
                'exists:departments,id',
            ],
            'employee_id' => [
                'required',
                'string',
                'max:255',
            ],
            'employee_firstname' => 'required|string|max:255',
            'employee_middlename' => 'required|string|max:255',
            'employee_lastname' => 'required|string|max:255',
            'employee_rfid' => 'required|string|max:255',
            'employee_photo' => 'nullable|image|max:2048', // Validation for image upload
        ]);


        
        // Find the existing employee record
        $employee = Employee::findOrFail($id);

        // Handle file upload if 'employee_photo' is present
        if ($request->hasFile('employee_photo')) {
            // Delete the old photo if it exists
            if ($employee->employee_photo && Storage::exists('public/employee_photo/' . $employee->employee_photo)) {
                Storage::delete('public/employee_photo/' . $employee->employee_photo);
            }

            $fileNameWithExt = $request->file('employee_photo')->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('employee_photo')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('employee_photo')->storeAs('public/employee_photo', $fileNameToStore);
        } else {
            $fileNameToStore = $employee->employee_photo; // Keep the current photo if no new photo is uploaded
        }

        // Check if an employee with the same employee_id or employee_rfid already exists, excluding the current employee
        $existingEmployeeById = Employee::where('employee_id', $request->input('employee_id'))->where('id', '!=', $id)->first();
        $existingEmployeeByRfid = Employee::where('employee_rfid', $request->input('employee_rfid'))->where('id', '!=', $id)->first();

        if (!$existingEmployeeById && !$existingEmployeeByRfid) {
            // Update employee attributes
            $employee->school_id = $request->input('school_id');
            $employee->department_id = $request->input('department_id');
            $employee->employee_id = $request->input('employee_id');
            $employee->employee_firstname = $request->input('employee_firstname');
            $employee->employee_middlename = $request->input('employee_middlename');
            $employee->employee_lastname = $request->input('employee_lastname');
            $employee->employee_rfid = $request->input('employee_rfid');
            $employee->employee_photo = $fileNameToStore;
            $employee->save();


            if (Auth::user()->hasRole('admin')) {
                
                return redirect()->route('admin.employee.index')
                ->with('success', 'Employee updated successfully.');

            } else {

                return redirect()->route('admin_staff.employee.index')
                ->with('success', 'Employee updated successfully.');

            }

        } else {
            $errorMessage = '';
            if ($existingEmployeeById) {
                $employeeName = $existingEmployeeById->employee_firstname . ' ' . $existingEmployeeById->employee_lastname;
                $errorMessage .= 'Employee ID ' . $request->input('employee_id') . ' is already taken by ' . $employeeName . '. ';
            }
            if ($existingEmployeeByRfid) {
                $employeeName = $existingEmployeeByRfid->employee_firstname . ' ' . $existingEmployeeByRfid->employee_lastname;
                $errorMessage .= 'RFID ' . $request->input('employee_rfid') . ' is already taken by ' . $employeeName . '. ';
            }

            if (Auth::user()->hasRole('admin')) {

                return redirect()->route('admin.employee.index')
                ->with('error', $errorMessage . 'Try again.');

            } else {

                return redirect()->route('admin_staff.employee.index')
                ->with('error', $errorMessage . 'Try again.');

            }
            
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        // Determine the route and role-based message
        $route = Auth::user()->hasRole('admin') ? 'admin.employee.index' : 'admin_staff.employee.index';

        try {
            // Find the employee or throw a 404 exception
            $employee = Employee::findOrFail($id);

            // Attempt to delete the employee
            $employee->delete();

            // Return success message based on user role
            return redirect()->route($route)->with('success', 'Employee deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where the employee is not found
            return redirect()->route($route)->with('error', 'Employee not found.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle SQL exceptions
            if ($e->getCode() == '23000') {
                return redirect()->route($route)->with('error', 'Cannot delete employee due to a foreign key constraint violation.');
            }

            // Handle other types of SQL exceptions
            return redirect()->route($route)->with('error', 'An unexpected error occurred while trying to delete the employee.');

        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->route($route)->with('error', 'An unexpected error occurred.');
        }       
         
    }

    public function deleteAll(Request $request)
    {
        $count = Employee::count();

        if ($count === 0) {
            return redirect()->route('admin.employee.index')->with('info', 'There are no employee/s to delete.');
        }
        else{
            
            Employee::truncate();
            return redirect()->route('admin.employee.index')->with('success', 'All employee/s deleted successfully.');
        }

        
    }


        public function employee_insertPhoto()
    {
        // Path to your photos directory
        $photosDirectory = storage_path('app/public/employee_photo/');

        // Directory where photos are currently stored (your desktop)
        $sourceDirectory = 'D:\SAP\\'; // Ensure trailing backslash

        // Ensure the photos directory exists
        if (!is_dir($photosDirectory)) {
            mkdir($photosDirectory, 0755, true);
        }

        // Get all files from the source directory
        $files = scandir($sourceDirectory);

        foreach ($files as $file) {
            // Skip '.' and '..' from the list
            if ($file === '.' || $file === '..') {
                continue;
            }

            // Full path to the source file
            $sourceFilePath = $sourceDirectory . $file;

            // Check if the file exists
            if (file_exists($sourceFilePath)) {
                // Extract the employee_id from the filename (assuming format 'employee_id_photo.ext')
                $employeeId = explode('_', pathinfo($file, PATHINFO_FILENAME))[0];

                // Generate a timestamp
                $timestamp = now()->format('Ymd_His');

                // Extract the file extension
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                // Create a new filename with timestamp
                $newFilename = $timestamp . '_' . pathinfo($file, PATHINFO_FILENAME) . '.' . $extension;

                // Full path to the destination file
                $destinationFilePath = $photosDirectory . $newFilename;

                // Move and rename the file
                rename($sourceFilePath, $destinationFilePath);

                // Update the employee record with the new filename
                $employee = Employee::find($employeeId); // Fetch employee using extracted employeeId
                if ($employee) {
                    $employee->employee_photo = $newFilename;
                    $employee->save();
                } else {
                    // Handle the case where the employee does not exist
                    // You might log an error or handle the missing employee
                }
            } else {
                // Handle the case where the file does not exist
                // You might log an error or handle the missing file
            }
        }

        return redirect()->back()->with('success', 'Photos inserted successfully.');
    }





    public function employee_login() 
    {
        return view('auth.login_employee');
    }

    public function login_employee(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'employee_lastname' => 'required',
        ]);

        $employee_id = strtoupper($request->employee_id);
        $employee_lastname = strtoupper($request->employee_lastname);

        $employee = Employee::where('employee_id', $employee_id)
                            ->where('employee_lastname', $employee_lastname)
                            ->first();

        

        if (!$employee) {
            return redirect()->back()->withErrors(['error' => 'Invalid credentials.']);
        }

        if (!$employee->hasRole('employee')) {
            $employee->assignRole('employee'); // Assign the role if not already assigned
        }

        Auth::guard('employee')->login($employee);

        $request->session()->regenerate();
        
         $uri = '/employee/dashboard';
        $hashedUri = hash('md5', $uri);

        // Redirect with the hash
        return redirect()->route('employee.dashboard', ['' => $hashedUri]);
    }


    public function logoutEmployee(Request $request)
    {
        //session()->flush(); 
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.login.portal'); // Ensure proper redirection to the login page
    }




    public function employee_dashboard()
    {
        if (Auth::user()->hasRole('employee')) {
           return view('Admin.employee.dashboard');
        }         
    }

}
