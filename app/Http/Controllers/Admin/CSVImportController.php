<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Employee;
use App\Models\Admin\Department;
use Illuminate\Support\Facades\Validator;

class CSVImportController extends Controller
{
    public function import(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'csv_file' => 'required|mimes:csv,txt',
        // ]);
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ], [
            'csv_file.mimes' => 'The csv file field must be a file of type: csv, txt.',
        ]);
        // Check if file upload was successful
        if ($request->file('csv_file')->isValid()) {
            // Process the CSV file
            $path = $request->file('csv_file')->getRealPath();
            $data = array_map('str_getcsv', file($path));

            // Check if CSV data is valid
            if (empty($data) || count($data) < 2) { // Assuming at least header and one row of data
                return redirect()->back()->with('error', 'CSV file is empty or invalid.');
            }

            // Validate CSV header
            $header = array_shift($data);
            $validator = Validator::make($header, [
                'school_id',
                'department_id',
                'employee_id',
                'employee_firstname',
                'employee_middlename',
                'employee_lastname',
                'employee_rfid',
                'employee_photo',
            ]);

            // if ($validator->fails()) {
            //     return redirect()->back()->withErrors($validator);
            // }

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', $validator->errors()->first()); // Custom error message from validator
            }

            

            // Process each row of data
            foreach ($data as $row) {
                // Create or update employee
                Employee::updateOrCreate([
                    'employee_id' => $row[2] // Assuming employee_id is unique
                ], [
                    'school_id' => $row[0],
                    'department_id' => $row[1],
                    'employee_firstname' => $row[3],
                    'employee_middlename' => $row[4],
                    'employee_lastname' => $row[5],
                    'employee_rfid' => $row[6],
                    'employee_photo' => $row[7],
                ]);
            }

            // Redirect back with success message
            return redirect()->back()->with('success', 'CSV file imported successfully.');
        } else {
            // File upload not successful
            return redirect()->back()->with('error', 'File upload failed. Please try again.');
        }
    }


    public function importDepartment(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'csv_file' => 'required|mimes:csv,txt',
        // ]);
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ], [
            'csv_file.mimes' => 'The csv file field must be a file of type: csv, txt.',
        ]);
        // Check if file upload was successful
        if ($request->file('csv_file')->isValid()) {
            // Process the CSV file
            $path = $request->file('csv_file')->getRealPath();
            $data = array_map('str_getcsv', file($path));

            // Check if CSV data is valid
            if (empty($data) || count($data) < 2) { // Assuming at least header and one row of data
                return redirect()->back()->with('error', 'CSV file is empty or invalid.');
            }

            // Validate CSV header
            $header = array_shift($data);
            $validator = Validator::make($header, [
                'school_id',
                'department_id',
                'department_abbreviation',
                'department_name',
                'dept_identifier',
            ]);

            // if ($validator->fails()) {
            //     return redirect()->back()->withErrors($validator);
            // }

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', $validator->errors()->first()); // Custom error message from validator
            }

            
            foreach ($data as $row) {
                Department::updateOrCreate([
                    'department_id' => $row[1], // Using department_id as the unique identifier
                ], [
                    'school_id' => $row[0],
                    'department_abbreviation' => $row[2],
                    'department_name' => $row[3],
                    'dept_identifier' => $row[4],
                ]);
            }
            // Process each row of data
            // foreach ($data as $row) {
            //     // Create or update employee
            //     Department::updateOrCreate([
            //         'department_id' => $row[1] // Assuming employee_id is unique
            //     ], [
            //         'school_id' => $row[0],
            //         'department_abbreviation' => $row[2],
            //         'department_name' => $row[3],
            //         'dept_identifier' => $row[4],
            //     ]);
            // }

            // Redirect back with success message
            return redirect()->back()->with('success', 'CSV file imported successfully.');
        } else {
            // File upload not successful
            return redirect()->back()->with('error', 'File upload failed. Please try again.');
        }
    }
}
