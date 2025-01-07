<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Admin\DepartmentWorkingHour;
use Illuminate\Support\Facades\Auth;

class WorkingHourController extends Controller
{
    public function index()
    {
        return view('Admin.department.working_hour');
    }

    public function store(Request $request)
    {
        
        if (Auth::user()->hasRole('admin')) 
        {
        
            // Validate input data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
                'department_id' => 'required|exists:departments,id',
                'day_of_week' => 'required|integer|between:0,6', // Assuming 0 to 6 for Sunday to Saturday
                'morning_start_time' => 'nullable',
                'morning_end_time' => 'nullable',
                'afternoon_start_time' => 'nullable',
                'afternoon_end_time' => 'nullable',
            ]);

            // Check if a schedule already exists for the given department and day of the week
            $existingDay = DepartmentWorkingHour::where('department_id', $request->input('department_id'))
                                                ->where('day_of_week', $request->input('day_of_week'))
                                                ->first();

            if (!$existingDay) {
                // Create a new working hour record
                $working_hour = new DepartmentWorkingHour();
                $working_hour->school_id = $request->input('school_id');
                $working_hour->department_id = $request->input('department_id');
                $working_hour->day_of_week = $request->input('day_of_week');
                $working_hour->morning_start_time = $request->input('morning_start_time');
                $working_hour->morning_end_time = $request->input('morning_end_time');
                $working_hour->afternoon_start_time = $request->input('afternoon_start_time');
                $working_hour->afternoon_end_time = $request->input('afternoon_end_time');
                $working_hour->save();

                return redirect()->route('admin.workinghour.index')
                    ->with('success', 'Schedule created successfully.');
            } else {
                // Redirect with error if a schedule already exists for the department and day
                $dayOfWeek = $request->input('day_of_week');
                switch ($dayOfWeek) {
                    case 0:
                        $dayName = 'Sunday';
                        break;
                    case 1:
                        $dayName = 'Monday';
                        break;
                    case 2:
                        $dayName = 'Tuesday';
                        break;
                    case 3:
                        $dayName = 'Wednesday';
                        break;
                    case 4:
                        $dayName = 'Thursday';
                        break;
                    case 5:
                        $dayName = 'Friday';
                        break;
                    case 6:
                        $dayName = 'Saturday';
                        break;
                    default:
                        $dayName = 'Unknown'; 
                }
                return redirect()->route('admin.workinghour.index')
                    ->with('error', ucfirst($dayName) . ' schedule already exists for this department.');
            }




        } else if (Auth::user()->hasRole('admin_staff')) {

            // Validate input data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
                'department_id' => 'required|exists:departments,id',
                'day_of_week' => 'required|integer|between:0,6', // Assuming 0 to 6 for Sunday to Saturday
                'morning_start_time' => 'nullable',
                'morning_end_time' => 'nullable',
                'afternoon_start_time' => 'nullable',
                'afternoon_end_time' => 'nullable',
                // 'morning_start_time' => 'required|date_format:H:i',
                // 'morning_end_time' => 'required|date_format:H:i',
                // 'afternoon_start_time' => 'required|date_format:H:i',
                // 'afternoon_end_time' => 'required|date_format:H:i',
            ]);

            // Check if a schedule already exists for the given department and day of the week
            $existingDay = DepartmentWorkingHour::where('department_id', $request->input('department_id'))
                                                ->where('day_of_week', $request->input('day_of_week'))
                                                ->first();

            if (!$existingDay) {
                // Create a new working hour record
                $working_hour = new DepartmentWorkingHour();
                $working_hour->school_id = $request->input('school_id');
                $working_hour->department_id = $request->input('department_id');
                $working_hour->day_of_week = $request->input('day_of_week');
                $working_hour->morning_start_time = $request->input('morning_start_time');
                $working_hour->morning_end_time = $request->input('morning_end_time');
                $working_hour->afternoon_start_time = $request->input('afternoon_start_time');
                $working_hour->afternoon_end_time = $request->input('afternoon_end_time');
                $working_hour->save();

                return redirect()->route('admin_staff.workinghour.index')
                    ->with('success', 'Schedule created successfully.');
            } else {
                // Redirect with error if a schedule already exists for the department and day
                $dayOfWeek = $request->input('day_of_week');
                switch ($dayOfWeek) {
                    case 0:
                        $dayName = 'Sunday';
                        break;
                    case 1:
                        $dayName = 'Monday';
                        break;
                    case 2:
                        $dayName = 'Tuesday';
                        break;
                    case 3:
                        $dayName = 'Wednesday';
                        break;
                    case 4:
                        $dayName = 'Thursday';
                        break;
                    case 5:
                        $dayName = 'Friday';
                        break;
                    case 6:
                        $dayName = 'Saturday';
                        break;
                    default:
                        $dayName = 'Unknown'; 
                }
                return redirect()->route('admin_staff.workinghour.index')
                    ->with('error', ucfirst($dayName) . ' schedule already exists for this department.');
            }
        }


    }


    public function update(Request $request, $id)
    {
        
        if (Auth::user()->hasRole('admin')) 
        {
            // Validate input data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
                'department_id' => 'required|exists:departments,id',
                'day_of_week' => 'required|integer|between:0,6', // Assuming 0 to 6 for Sunday to Saturday
                'morning_start_time' => 'nullable',
                'morning_end_time' => 'nullable',
                'afternoon_start_time' => 'nullable',
                'afternoon_end_time' => 'nullable',
            ]);

            // Find the existing record by $id
            $working_hour = DepartmentWorkingHour::findOrFail($id);

            // Check if a schedule already exists for the given department and day of the week
            $existingDay = DepartmentWorkingHour::where('department_id', $request->input('department_id'))
                                                ->where('day_of_week', $request->input('day_of_week'))
                                                ->where('id', '!=', $id) // Exclude current record from check
                                                ->first();

            if ($existingDay) {
                // If a schedule already exists, redirect with error
                $dayOfWeek = $request->input('day_of_week');
                switch ($dayOfWeek) {
                    case 0:
                        $dayName = 'Sunday';
                        break;
                    case 1:
                        $dayName = 'Monday';
                        break;
                    case 2:
                        $dayName = 'Tuesday';
                        break;
                    case 3:
                        $dayName = 'Wednesday';
                        break;
                    case 4:
                        $dayName = 'Thursday';
                        break;
                    case 5:
                        $dayName = 'Friday';
                        break;
                    case 6:
                        $dayName = 'Saturday';
                        break;
                    default:
                        $dayName = 'Unknown';
                }

                return redirect()->route('admin.workinghour.index')
                    ->with('error', ucfirst($dayName) . ' schedule already exists for this department.');
            }
   
            $working_hour->school_id = $request->input('school_id');
            $working_hour->department_id = $request->input('department_id');
            $working_hour->day_of_week = $request->input('day_of_week');
            $working_hour->morning_start_time = $request->input('morning_start_time');
            $working_hour->morning_end_time = $request->input('morning_end_time');
            $working_hour->afternoon_start_time = $request->input('afternoon_start_time');
            $working_hour->afternoon_end_time = $request->input('afternoon_end_time');
            $working_hour->save();

            return redirect()->route('admin.workinghour.index')
                ->with('success', 'Schedule updated successfully.');

        } else if (Auth::user()->hasRole('admin_staff')) {
            // Validate input data
            $request->validate([
                'school_id' => 'required|exists:schools,id',
                'department_id' => 'required|exists:departments,id',
                'day_of_week' => 'required|integer|between:0,6', // Assuming 0 to 6 for Sunday to Saturday
                'morning_start_time' => 'nullable',
                'morning_end_time' => 'nullable',
                'afternoon_start_time' => 'nullable',
                'afternoon_end_time' => 'nullable',
            ]);

            // Find the existing record by $id
            $working_hour = DepartmentWorkingHour::findOrFail($id);

            // Check if a schedule already exists for the given department and day of the week
            $existingDay = DepartmentWorkingHour::where('department_id', $request->input('department_id'))
                                                ->where('day_of_week', $request->input('day_of_week'))
                                                ->where('id', '!=', $id) // Exclude current record from check
                                                ->first();

            if ($existingDay) {
                // If a schedule already exists, redirect with error
                $dayOfWeek = $request->input('day_of_week');
                switch ($dayOfWeek) {
                    case 0:
                        $dayName = 'Sunday';
                        break;
                    case 1:
                        $dayName = 'Monday';
                        break;
                    case 2:
                        $dayName = 'Tuesday';
                        break;
                    case 3:
                        $dayName = 'Wednesday';
                        break;
                    case 4:
                        $dayName = 'Thursday';
                        break;
                    case 5:
                        $dayName = 'Friday';
                        break;
                    case 6:
                        $dayName = 'Saturday';
                        break;
                    default:
                        $dayName = 'Unknown';
                }

                return redirect()->route('admin_staff.workinghour.index')
                    ->with('error', ucfirst($dayName) . ' schedule already exists for this department.');
            }

            // Update the fields
            $working_hour->school_id = $request->input('school_id');
            $working_hour->department_id = $request->input('department_id');
            $working_hour->day_of_week = $request->input('day_of_week');
            $working_hour->morning_start_time = $request->input('morning_start_time');
            $working_hour->morning_end_time = $request->input('morning_end_time');
            $working_hour->afternoon_start_time = $request->input('afternoon_start_time');
            $working_hour->afternoon_end_time = $request->input('afternoon_end_time');
            $working_hour->save();

            return redirect()->route('admin_staff.workinghour.index')
                ->with('success', 'Schedule updated successfully.');
        }

    }


    public function destroy(string $id)
    {

        
            $schedule = DepartmentWorkingHour::findOrFail($id);

            $schedule->delete();

            if (Auth::user()->hasRole('admin')) {

                return redirect()->route('admin.workinghour.index')->with('success', 'Schedule deleted successfully.');

            } else if (Auth::user()->hasRole('admin_staff')) {
            
                return redirect()->route('admin_staff.workinghour.index')->with('success', 'Schedule deleted successfully.');
            }
    }


}
