<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use \App\Models\Admin\Student;
use \App\Models\Admin\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.student.index');
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
                'course_id' => 'required|exists:courses,id',
                'student_id' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'student_lastname' => 'required|string|max:255',
                'student_firstname' => 'required|string|max:255',
                'student_middlename' => 'required|string|max:255',
                'student_year_grade' => 'required|string|max:255',
                'student_rfid' => 'required|string|max:255',
                'student_status' => 'required|string|max:255',
                'student_photo' => 'image|max:2048', // Example: validation for image upload
            ]);

            // Handle file upload if 'course_photo' is present
            if ($request->hasFile('student_photo')) {
                $fileNameWithExt = $request->file('student_photo')->getClientOriginalName();
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('student_photo')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('student_photo')->storeAs('public/student_photo', $fileNameToStore);
            } else {
                $fileNameToStore = 'user.png'; // Default file if no photo is uploaded
            }

            // Check if an course with the same course_id or course_rfid already exists
            $existingStudentById = Student::where('student_id', $request->input('student_id'))->first();
             $existingStudentByRfid = Student::where('student_rfid', $request->input('student_rfid'))->first();

            if (!$existingStudentById && !$existingStudentByRfid) {
                $student = new Student();
                $student->course_id = $request->input('course_id');
                $student->student_id = $request->input('student_id');
                $student->student_firstname = $request->input('student_firstname');
                $student->student_middlename = $request->input('student_middlename');
                $student->student_lastname = $request->input('student_lastname');
                $student->student_rfid = $request->input('student_rfid');
                $student->student_year_grade = $request->input('student_year_grade');
                $student->student_status = $request->input('student_status');
                $student->student_photo = $fileNameToStore;
                $student->save();


                if (Auth::user()->hasRole('admin')) {
                    return redirect()->route('admin.student.index')
                    ->with('success', 'Student created successfully.');
                } else {
                    return redirect()->route('staff.student.index')
                    ->with('success', 'Student created successfully.');
                }
                
            } else {
                $errorMessage = '';
                if ($existingStudentById) {
                    $StudentName = $existingStudentById->student_lastname .' ' . $existingStudentById->student_firstname ;
                    $errorMessage .= 'Student ID ' . $request->input('student_id') . ' is already taken by ' . $StudentName . '. ';
                }

                if ($existingStudentByRfid) {
                    $StudentName = $existingStudentByRfid->student_lastname .' ' . $existingStudentByRfid->student_firstname ;
                    $errorMessage .= 'Student RFID No ' . $request->input('student_rfid') . ' is already taken by ' . $StudentName . '. ';
                }


                
                if (Auth::user()->hasRole('admin')) {
                    return redirect()->route('admin.student.index')
                    ->with('error', $errorMessage . 'Try again.');
                } else {
                    return redirect()->route('staff.student.index')
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
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'student_id' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'student_lastname' => 'required|string|max:255',
                'student_firstname' => 'required|string|max:255',
                'student_middlename' => 'required|string|max:255',
                'student_year_grade' => 'required|string|max:255',
                'student_rfid' => 'required|string|max:255',
                'student_status' => 'required|string|max:255',
                'student_photo' => 'image|max:2048', // Example: validation for image upload
            ]);


        
        // Find the existing student record
        $student = Student::findOrFail($id);

        $toInTdeptId = (int) $request->department_id;
        $course = Course::findOrFail($request->course_id); // Get the course by ID

        // Check if the course's department_id matches the department_id in the request
        if ($course->department_id !== $toInTdeptId) {
            $errorMessage = 'The selected course does not belong to the selected department.';
            return redirect()->route('admin.student.index')
                            ->with('error', $errorMessage . ' Try again.');
        } else {

            // Handle file upload if 'student_photo' is present
            if ($request->hasFile('student_photo')) {
                // Delete the old photo if it exists
                if ($student->student_photo && Storage::exists('public/student_photo/' . $student->student_photo)) {
                    Storage::delete('public/student_photo/' . $student->student_photo);
                }

                $fileNameWithExt = $request->file('student_photo')->getClientOriginalName();
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('student_photo')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('student_photo')->storeAs('public/student_photo', $fileNameToStore);
            } else {
                $fileNameToStore = $student->student_photo; // Keep the current photo if no new photo is uploaded
            }

            // Check if an student with the same student_id or student_rfid already exists, excluding the current student
            $existingStudentById = student::where('student_id', $request->input('student_id'))->where('id', '!=', $id)->first();
            $existingStudentByRfid = student::where('student_rfid', $request->input('student_rfid'))->where('id', '!=', $id)->first();

            if (!$existingStudentById && !$existingStudentByRfid) 
            {
                $student->course_id = $request->input('course_id');
                $student->student_id = $request->input('student_id');
                $student->student_firstname = $request->input('student_firstname');
                $student->student_middlename = $request->input('student_middlename');
                $student->student_lastname = $request->input('student_lastname');
                $student->student_rfid = $request->input('student_rfid');
                $student->student_year_grade = $request->input('student_year_grade');
                $student->student_status = $request->input('student_status');
                $student->student_photo = $fileNameToStore;
                $student->save();

                
                    if (Auth::user()->hasRole('admin')) {
                        return redirect()->route('admin.student.index')
                        ->with('success', 'Student updated successfully.');
                    } else {
                        return redirect()->route('staff.student.index')
                        ->with('success', 'Student updated successfully.');
                    }

            } else {
                $errorMessage = '';
                if ($existingStudentById) {
                    $StudentName = $existingStudentById->student_lastname .' ' . $existingStudentById->student_firstname ;
                    $errorMessage .= 'Student ID ' . $request->input('student_id') . ' is already taken by ' . $StudentName . '. ';
                }

                if ($existingStudentByRfid) {
                    $StudentName = $existingStudentByRfid->student_lastname .' ' . $existingStudentByRfid->student_firstname ;
                    $errorMessage .= 'Student RFID No ' . $request->input('student_rfid') . ' is already taken by ' . $StudentName . '. ';
                }


                if (Auth::user()->hasRole('admin')) {
                        return redirect()->route('admin.student.index')
                        ->with('error', $errorMessage . 'Try again.');
                    } else {
                        return redirect()->route('staff.student.index')
                        ->with('error', $errorMessage . 'Try again.');
                    }
            }
        }

            
  

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $student = Student::findOrFail($id);

        $student->delete();
        if (Auth::user()->hasRole('admin')) {

            return redirect()->route('admin.student.index')->with('success', 'Student deleted successfully.');
        } else {
            return redirect()->route('staff.student.index')->with('success', 'Student deleted successfully.');
        }
    }

    public function deleteAll(Request $request)
    {
        $count = Student::count();

        if ($count === 0) {
            return redirect()->route('admin.student.index')->with('info', 'There are no student/s to delete.');
        }
        else{
            
            Student::truncate();
            return redirect()->route('admin.student.index')->with('success', 'All student/s deleted successfully.');
        }

        
    }

}
