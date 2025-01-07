<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use \App\Models\Admin\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.course.index');
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
                'course_id' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'course_id' => 'required|string|max:255',
                'course_name' => 'required|string|max:255',
                'course_abbreviation' => 'required|string|max:255',
                'course_logo' => 'image|max:2048', // Example: validation for image upload
            ]);

            // Handle file upload if 'course_photo' is present
            if ($request->hasFile('course_logo')) {
                $fileNameWithExt = $request->file('course_logo')->getClientOriginalName();
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('course_logo')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('course_logo')->storeAs('public/course_logo', $fileNameToStore);
            } else {
                $fileNameToStore = 'user.png'; // Default file if no photo is uploaded
            }

            // Check if an course with the same course_id or course_rfid already exists
            $existingCourseById = Course::where('course_id', $request->input('course_id'))->first();

            if (!$existingCourseById) {
                $course = new Course();
                $course->school_id = $request->input('school_id');
                $course->department_id = $request->input('department_id');
                $course->course_id = $request->input('course_id');
                $course->course_abbreviation = $request->input('course_abbreviation');
                $course->course_name = $request->input('course_name');
                $course->course_logo = $fileNameToStore;
                $course->save();

                if (Auth::user()->hasRole('admin')) {
                    return redirect()->route('admin.course.index')
                        ->with('success', 'Course created successfully.');
                }
                else{
                    return redirect()->route('staff.course.index')
                        ->with('success', 'Course created successfully.');
                }
                    

            } else {
                $errorMessage = '';
                if ($existingCourseById) {
                    $courseName = $existingCourseById->course_abbreviation . ' ' . $existingCourseById->course_name;
                    $errorMessage .= 'Course ID ' . $request->input('course_id') . ' is already taken by ' . $courseName . '. ';
                }

                
                if (Auth::user()->hasRole('admin')) {
                    return redirect()->route('admin.course.index')
                    ->with('error', $errorMessage . 'Try again.');
                }
                else{
                    return redirect()->route('staff.course.index')
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
            'course_id' => [
                'required',
                'string',
                'max:255',
            ],
            'course_id' => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'course_abbreviation' => 'required|string|max:255',
            'course_logo' => 'image|max:2048', // Example: validation for image upload
        ]);


        
        // Find the existing course record
        $course = Course::findOrFail($id);

        // Handle file upload if 'course_photo' is present
        if ($request->hasFile('course_logo')) {
            // Delete the old photo if it exists
            if ($course->course_logo && Storage::exists('public/course_logo/' . $course->course_logo)) {
                Storage::delete('public/course_photo/' . $course->course_logo);
            }

            $fileNameWithExt = $request->file('course_logo')->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('course_logo')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('course_logo')->storeAs('public/course_logo', $fileNameToStore);
        } else {
            $fileNameToStore = $course->course_photo; // Keep the current photo if no new photo is uploaded
        }

        // Check if an course with the same course_id or course_rfid already exists, excluding the current course
        $existingCourseById = course::where('course_id', $request->input('course_id'))->where('id', '!=', $id)->first();

        if (!$existingCourseById) {
            // Update course attributes
            $course->school_id = $request->input('school_id');
            $course->department_id = $request->input('department_id');
            $course->course_id = $request->input('course_id');
            $course->course_abbreviation = $request->input('course_abbreviation');
            $course->course_name = $request->input('course_name');
            $course->course_logo = $fileNameToStore;
            $course->save();


            if (Auth::user()->hasRole('admin')){
                return redirect()->route('admin.course.index')
                ->with('success', 'Course updated successfully.');
            } else {
                return redirect()->route('staff.course.index')
                ->with('success', 'Course updated successfully.');
            }
            
        } else {
            $errorMessage = '';
            if ($existingCourseById) {
                $courseName = $existingCourseById->course_id . ' ' . $existingCourseById->course_name;
                $errorMessage .= 'Course ID ' . $request->input('course_id') . ' is already taken by ' . $courseName . '. ';
            }

            if (Auth::user()->hasRole('admin'))
            {
                return redirect()->route('admin.course.index')
                ->with('error', $errorMessage . 'Try again.');
            } else {
                return redirect()->route('staff.course.index')
                ->with('error', $errorMessage . 'Try again.');
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $course = Course::findOrFail($id);
        $course->delete();

        if (Auth::user()->hasRole('admin'))
        {
            return redirect()->route('admin.course.index')->with('success', 'Course deleted successfully.');
        }
        else {
            return redirect()->route('staff.course.index')->with('success', 'Course deleted successfully.');
        }
    }
}
