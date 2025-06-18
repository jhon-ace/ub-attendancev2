<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Admin\School; 
use \App\Models\Admin\Department;
use \App\Models\Admin\Employee;
use \App\Models\Admin\Student;
use \App\Models\Admin\EmployeeAttendanceTimeIn;
use \App\Models\Admin\EmployeeAttendanceTimeOut;
use \App\Models\Admin\StudentAttendanceTimeIn;
use \App\Models\Admin\StudentAttendanceTimeOut;
use \App\Models\Admin\DepartmentWorkingHour;
use \App\Models\Admin\GracePeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use \App\Models\Admin\Fingerprint;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function employee()
    {
        return view('Admin.attendance.employee_attendance');
    }

    public function employeelist()
    {
        return view('Admin.attendance.employee_attendance_for_payroll');
    }

    public function employeelistall()
    {
        return view('Admin.attendance.employee_attendance_for_payroll_all');
    }

    public function employeeSearch()
    {
         return view('Admin.attendance.employeeSearch');
    }

    public function student()
    {
        return view('Admin.attendance.student_attendance');
    }

        public function delete_attendance()
    {
        return view('Admin.delete_attendance.index');
    }

    public function portalTimeIn()
    {
        $current_date = now()->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d');

        
        $checkDateIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $current_date)->first();
        $checkDateOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $current_date)->first();


        if ($checkDateIn) 
        {
            $curdateDataIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $current_date)->get();
            $curdateDataOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $current_date)->get();
            
            return view('attendance_time_in', compact('curdateDataIn', 'curdateDataOut'));

        }

        return view('attendance_time_in', [
            'curdateDataIn' => [],
            'curdateDataOut' => [],
        ]);
  

    }

    public function portalTimeOut()
    {
        return view('attendance_time_out');
    }





public function submitPortalTimeIn(Request $request)
{
    $request->validate([
        'user_rfid' => 'required',
    ]);

    $rfid = $request->input('user_rfid');

    // Query to get the employee based on the RFID
    $employee = Employee::where('employee_rfid', $rfid)->first();
    $employees = Employee::where('employee_rfid', $rfid)->get();

    if ($employee) {
        // Get the current datetime in Kuala Lumpur timezone
        $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));

        // Format datetime for database insertion
        $formattedDateTime = $now->format('Y-m-d H:i:s');

        // Get the count of time-in records for today
        $timeInCount = EmployeeAttendanceTimeIn::where('employee_id', $employee->id)
            ->whereDate('check_in_time', $now->format('Y-m-d'))
            ->count();

        // Get the first time-in record for today
        $firstTimeIn = EmployeeAttendanceTimeIn::where('employee_id', $employee->id)
            ->whereDate('check_in_time', $now->format('Y-m-d'))
            ->first();

        // Get the count of time-out records for today
        $timeOutCount = EmployeeAttendanceTimeOut::where('employee_id', $employee->id)
            ->whereDate('check_out_time', $now->format('Y-m-d'))
            ->count();

        // Get the first time-in record for today
        $firstTimeOut = EmployeeAttendanceTimeOut::where('employee_id', $employee->id)
            ->whereDate('check_out_time', $now->format('Y-m-d'))
            ->first();

        // Get the last time-in record for today
        $lastTimeIn = EmployeeAttendanceTimeIn::where('employee_id', $employee->id)
            ->whereDate('check_in_time', $now->format('Y-m-d'))
            ->latest('check_in_time')
            ->first();

        

        $intervalAllowed = false;
        // Check interval for first check-out
        if ($firstTimeIn) {
            $checkInTime = new DateTime($firstTimeIn->check_in_time, new DateTimeZone('Asia/Kuala_Lumpur'));
            $interval = $now->diff($checkInTime);
            $minutes = $interval->i + ($interval->h * 60);
            if ($minutes >= 45) {
                $intervalAllowed = true;
            }
        }

        // Check if the employee has already checked out in the afternoon
        if ($timeInCount == 2 && $timeOutCount == 2) {
            return redirect()->route('admin.attendance.time-in.portal')->with('success', 'Attendance completed. Safe travels home!');
            // return response()->json([
            //     'message' => 'Already checked out in the afternoon. Go home safely!',
            // ], 403);
        }
        else if ($timeInCount == 1 && $timeOutCount == 1) {
            
            $intervalAllowed = false;

            if ($firstTimeOut) {
                $checkOutTime = new DateTime($firstTimeOut->check_out_time, new DateTimeZone('Asia/Kuala_Lumpur'));
                $interval = $now->diff($checkOutTime);
                $minutes = $interval->i + ($interval->h * 60);
                if ($minutes >= 45) {
                    $intervalAllowed = true;
                }
            }

            if ($intervalAllowed) 
            {
                // Second time-in (PM), no second check-out
                $attendanceIn = new EmployeeAttendanceTimeIn();
                $attendanceIn->employee_id = $employee->id;
                $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
                $attendanceIn->status = "On-campus";
                $attendanceIn->modification_status = "Unmodified";
                $attendanceIn->save();

                // return response()->json([
                //     'message' => 'PM Time-in recorded successfully.',
                //     'employee' => $employee,
                //     'check_in_time' => $formattedDateTime,
                // ], 200);
                return view('attendance-profile_time_in_employee', compact('employees'));
            } else {
                // return response()->json([
                //     'message' => 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out.',
                // ], 403);
                // return redirect()->route('admin.attendance.time-in.portal')->with('error', 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out!');
                return redirect()->route('admin.attendance.time-in.portal')->with('error', 'Please wait 45 minutes for Afternoon time in!');
            }
        } elseif ($timeInCount == 1 && $timeOutCount == 0) {
                if ($intervalAllowed) 
                {
                    // First check-out (AM)
                    $attendanceOut = new EmployeeAttendanceTimeOut();
                    $attendanceOut->employee_id = $employee->id;
                    $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
                    $attendanceOut->status = "Outside Campus";
                    $attendanceOut->modification_status = "Unmodified";
                    $attendanceOut->save();

                    // return response()->json([
                    //     'message' => 'AM Time-out recorded successfully.',
                    //     'employee' => $employee,
                    //     'check_out_time' => $formattedDateTime,
                    // ], 200);
                    return view('attendance-profile_time_out_employee', compact('employees'));
                } else {
                    // return response()->json([
                    //     'message' => 'Already Time In Morning.',
                    // ], 403);
                    return redirect()->route('admin.attendance.time-in.portal')->with('success', 'Already Timed In!');
                }
        } elseif ($timeInCount == 2 && $timeOutCount == 1) {
            // Check interval for second check-out
            $intervalAllowed = false;
            if ($lastTimeIn) {
                $checkInTime = new DateTime($lastTimeIn->check_in_time, new DateTimeZone('Asia/Kuala_Lumpur'));
                $interval = $now->diff($checkInTime);
                $minutes = $interval->i + ($interval->h * 60);
                if ($minutes >= 45) {
                    $intervalAllowed = true;
                }
            }

            if ($intervalAllowed) {
                // Second check-out (PM)
                $attendanceOut = new EmployeeAttendanceTimeOut();
                $attendanceOut->employee_id = $employee->id;
                $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
                $attendanceOut->status = "Outside Campus";
                $attendanceOut->modification_status = "Unmodified";
                $attendanceOut->save();

                // return response()->json([
                //     'message' => 'PM Time-out recorded successfully.',
                //     'employee' => $employee,
                //     'check_out_time' => $formattedDateTime,
                // ], 200);
                 return view('attendance-profile_time_out_employee', compact('employees'));
            } else {
                // return response()->json([
                //     'message' => 'Already Time-in Afternoon.',
                // ], 403);
                return redirect()->route('admin.attendance.time-in.portal')->with('success', 'Already Timed In!');
            }

        } else {
            // First time-in (AM)
            $attendanceIn = new EmployeeAttendanceTimeIn();
            $attendanceIn->employee_id = $employee->id;
            $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
            $attendanceIn->status = "On-campus";
            $attendanceIn->modification_status = "Unmodified";
            $attendanceIn->save();

            return view('attendance-profile_time_in_employee', compact('employees'));
            // return response()->json([
            //     'message' => 'AM Time-in recorded successfully.',
            //     'employee' => $employee,
            //     'check_in_time' => $formattedDateTime,
            // ], 200);
        }
    } else {
        // Handle case where employee with given RFID is not found
        // return response()->json(['error' => 'Employee not found.'], 404);
        return redirect()->route('admin.attendance.time-in.portal')->with('error', 'RFID not recognized!');
    }
}




// public function submitPortalTimeIn(Request $request)
// {
//     // Get the current timestamp and date in Asia/Kuala_Lumpur timezone
//     $current_time = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
//     $current_timestamp = $current_time->getTimestamp();
//     $current_date = $current_time->format('Y-m-d');
//     $current_day = (int) $current_time->format('w'); // Numeric representation of the day of the week (0 for Sunday, 6 for Saturday)

//     $rfid = $request->input('user_rfid');

//     // Validate the employee's RFID
//     $employee = Employee::where('employee_rfid', $rfid)->first();

//     if ($employee) {
//         // Adjust the current day to match the range of `day_of_week` in your `working_hour` table (0 to 6)
//         $current_day_of_week = $current_day == 0 ? 7 : $current_day; // Convert 0 (Sunday) to 7 for consistency with your `day_of_week` values

//         // Retrieve the department's working hours for the current day
//         $workingHour = DepartmentWorkingHour::where('department_id', $employee->department_id)
//             ->where('day_of_week', $current_day_of_week - 1) // Adjust to match the `day_of_week` range (0 to 6)
//             ->first();

//         if ($workingHour) {
//             // Extract working hours for the morning and afternoon
//             $morning_start_time = DateTime::createFromFormat('H:i:s', $workingHour->morning_start_time, new DateTimeZone('Asia/Kuala_Lumpur'))->getTimestamp();
//             $morning_end_time = DateTime::createFromFormat('H:i:s', $workingHour->morning_end_time, new DateTimeZone('Asia/Kuala_Lumpur'))->getTimestamp();
//             $afternoon_start_time = DateTime::createFromFormat('H:i:s', $workingHour->afternoon_start_time, new DateTimeZone('Asia/Kuala_Lumpur'))->getTimestamp();
//             $afternoon_end_time = DateTime::createFromFormat('H:i:s', $workingHour->afternoon_end_time, new DateTimeZone('Asia/Kuala_Lumpur'))->getTimestamp();

//             // Check if there are any attendance records for today
//             $checkDateIn = EmployeeAttendanceTimeIn::where('employee_id', $employee->id)
//                 ->whereDate('check_in_time', $current_date)
//                 ->first();

//             if ($checkDateIn) {
//                 // Calculate the time difference since the last check-in
//                 $last_check_in_time = DateTime::createFromFormat('Y-m-d H:i:s', $checkDateIn->check_in_time, new DateTimeZone('Asia/Kuala_Lumpur'))->getTimestamp();
//                 $interval_minutes = ($current_timestamp - $last_check_in_time) / 60; // Convert seconds to minutes

//                 // Check if the interval is less than 45 minutes
//                 if ($interval_minutes < 45) {
//                     return response()->json(['message' => 'Cannot check in within 45 minutes of last check-in.'], 400);
//                 } else {
//                     // Determine the session based on current time
//                     $session = '';
//                     if ($current_timestamp >= $morning_start_time && $current_timestamp <= $morning_end_time) {
//                         $session = 'morning';
//                     } elseif ($current_timestamp >= $afternoon_start_time && $current_timestamp <= $afternoon_end_time) {
//                         $session = 'afternoon';
//                     }

//                     // Handle check-in based on session
//                     if ($session === 'morning') {
//                         // Record time-out for morning session
//                         EmployeeAttendanceTimeOut::create([
//                             'employee_id' => $employee->id,
//                             'check_out_time' => $current_time->format('Y-m-d H:i:s'),
//                             'status' => 'Checked Out'
//                         ]);
//                         return response()->json(['message' => 'Checked out successfully from morning session.']);
//                     } elseif ($session === 'afternoon') {
//                         // Check if it's within 45 minutes of afternoon session start
//                         if ($current_timestamp <= $afternoon_start_time + 45 * 60) {
//                             return response()->json(['message' => 'Wait for 45 minutes to check in for afternoon session.'], 400);
//                         } else {
//                             // Record time-in for afternoon session
//                             EmployeeAttendanceTimeIn::create([
//                                 'employee_id' => $employee->id,
//                                 'check_in_time' => $current_time->format('Y-m-d H:i:s'),
//                                 'status' => 'Checked In'
//                             ]);
//                             return response()->json(['message' => 'Checked in successfully for afternoon session.']);
//                         }
//                     } else {
//                         // Allow check-out if current time is after morning_end_time
//                         if ($current_timestamp >= $morning_end_time) {
//                             EmployeeAttendanceTimeOut::create([
//                                 'employee_id' => $employee->id,
//                                 'check_out_time' => $current_time->format('Y-m-d H:i:s'),
//                                 'status' => 'Checked Out'
//                             ]);
//                             return response()->json(['message' => 'Checked out successfully from morning session.']);
//                         } else {
//                             return response()->json(['message' => 'Cannot check in outside working hours.'], 400);
//                         }
//                     }
//                 }
//             } else {
//                 // If there is no check-in for today, record the time-in for morning session
//                 EmployeeAttendanceTimeIn::create([
//                     'employee_id' => $employee->id,
//                     'check_in_time' => $current_time->format('Y-m-d H:i:s'),
//                     'status' => 'Checked In'
//                 ]);
//                 return response()->json(['message' => 'Checked in successfully for morning session.']);
//             }
//         } else {
//             return response()->json(['message' => 'No working hours found for the current day.'], 404);
//         }
//     } else {
//         return response()->json(['message' => 'RFID not found or invalid.'], 404);
//     }
// }






public function submitPortalTimeOut(Request $request)
{
    // Hardcoded credentials for validation (not from user input)
    // $validEmail = 'jacasabuena@cec.edu.ph';
    // $validPassword = 'administrator';

    // Validate incoming request data
    $request->validate([
        'user_rfid' => 'required',
    ]);

    // Attempt to retrieve user from database based on hardcoded email
    // $user = User::where('email', $validEmail)->first();

    // // Check if retrieved user exists and validate password
    // if ($user) {
    //     // Use the check method on the retrieved user's hashed password
    //     if (password_verify($validPassword, $user->password)) {
    //         // Check if user has admin role (assuming you have a hasRole method)
    //         if ($user->hasRole('admin')) {
                // Check if employee with the specified RFID exists
                $rfid = $request->input('user_rfid');

                $employees = Employee::where('employee_rfid', $rfid)->get();
                $employees2 = Employee::where('employee_rfid', $rfid)->first();
                
                if ($employees2) {
                    // Insert attendance record
                    $status ="Off-campus";

                    $attendance = new EmployeeAttendanceTimeOut();
                    $attendance->employee_id = $employees2->id;
                    $attendance->check_out_time = Carbon::now('Asia/Kuala_Lumpur');
                    $attendance->status = $status; 
                    $attendance->save();
                    
                    return view('attendance-profile_time_out_employee', compact('employees'));
                } 


                $students = Student::where('student_rfid', $rfid)->get();
                $student_for_attendance_out = Student::where('student_rfid', $rfid)->first();

                if ($student_for_attendance_out) {
                        $status = "Off-campus";
 
                        $attendance = new StudentAttendanceTimeOut();
                        $attendance->student_id = $student_for_attendance_out->id;
                        $attendance->check_out_time = Carbon::now('Asia/Kuala_Lumpur');
                        $attendance->status = $status; 
                        $attendance->save();

                        return view('attendance-profile_time_out_student', compact('students'));
                }


                return redirect()->route('admin.attendance.time-out.portal')->with('error', 'Employee not found.');



    //         } else {
    //             return redirect()->back()->with('error', 'Unauthorized access.');
    //         }
    //     } else {
    //         // Invalid password
    //         return redirect()->back()->with('error', 'Invalid email or password.');
    //     }
    // } else {
    //     // User not found
    //     return redirect()->back()->with('error', 'Invalid email or password.');
    // }
}




        // $employeeRfid = $request->input('employee_rfid');
        // $employees = Employee::where('employee_rfid', $employeeRfid)->get();
        
        // if($employees->isNotEmpty()) {
        //     return redirect()->route('admin.attendance.employee_attendance.portal')->with('success', 'Employee found successfully.');
        // } else {
        //     return redirect()->route('admin.attendance.employee_attendance.portal')->with('error', 'Employee not found.');
        // }
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function modifyAttendance(Request $request)
    {
        $validatedData = $request->validate([
            'selected_date' => 'required|date',
            'status' => 'required|string',
            'employee_id' => 'required|integer',
        ]);

        $employee_id = $validatedData['employee_id'];
        $employees = Employee::where('id', $employee_id)->first();

        if ($employees) {
            $dateOnly = Carbon::parse($validatedData['selected_date'])->format('Y-m-d');
            $dayOfWeek = Carbon::parse($validatedData['selected_date'])->dayOfWeek;
            
            // Check if there are already 2 check-in and 2 check-out records for the employee on the selected date
            $checkInCount = EmployeeAttendanceTimeIn::where('employee_id', $employee_id)
                ->whereDate('check_in_time', $dateOnly)
                ->count();

            $checkOutCount = EmployeeAttendanceTimeOut::where('employee_id', $employee_id)
                ->whereDate('check_out_time', $dateOnly)
                ->count();

            // if ($checkInCount == 0 && $checkOutCount == 0) {
                $currentDayOfWeek = now()->dayOfWeek;

                // Retrieve the DepartmentWorkingHour record for the current department and day of the week
                $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $employees->department_id)
                    ->where('day_of_week', $dayOfWeek)
                    ->first();

                // Create AM check-in record
                if($checkInCount == 0 && $checkOutCount == 0){

                    //morning & afternoon start time/time-in
                    $attendanceInAm = new EmployeeAttendanceTimeIn();
                    $attendanceInAm->employee_id = $employees->id;
                    if($departmentWorkingHour){
                        $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_start_time;
                    }else {
                        $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' 00:00:00';
                    }
                    $attendanceInAm->status = $validatedData['status'];
                    $attendanceInAm->save();

                     $attendanceOutAm = new EmployeeAttendanceTimeOut();
                    $attendanceOutAm->employee_id = $employees->id;
                    if($departmentWorkingHour){
                        $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_end_time;
                    } else {
                        $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' 00:00:00';
                    }
                    $attendanceOutAm->status = $validatedData['status'];
                    $attendanceOutAm->save();

                

                
                    //morning & afternoon end time/time-out
                    $attendanceInAm = new EmployeeAttendanceTimeIn();
                    $attendanceInAm->employee_id = $employees->id;
                    if($departmentWorkingHour){
                        $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_start_time;
                    }else {
                        $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' 00:00:00';
                    }
                    $attendanceInAm->status = $validatedData['status'];
                    $attendanceInAm->save();

                    $attendanceOutAm = new EmployeeAttendanceTimeOut();
                    $attendanceOutAm->employee_id = $employees->id;
                    if($departmentWorkingHour){
                        $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_end_time;
                    } else {
                        $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' 00:00:00';
                    }
                    $attendanceOutAm->status = $validatedData['status'];
                    $attendanceOutAm->save();

                } 


                if($checkInCount == 2 && $checkOutCount == 2){
                    return back()->with('error', 'Cannot modify date: check-in and check-out records exist.');
                }

                //return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Date Successfully modified!');
                return back()->with('success', 'Full Day Leave Successfully Added!');
        }

        return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Employee not found.');
    }



    // public function modifyAttendanceHalfDay(Request $request)
    // {

    //     $validatedData = $request->validate([
    //         'selected_date' => 'required|date',
    //         'status' => 'required|string',
    //         'employee_id' => 'required|integer',
    //         'day_of_week' => 'required|integer',

    //     ]);

      
    //     $employee_id = $validatedData['employee_id'];
    //     $employees = Employee::where('id', $employee_id)->first();

    //     if ($employees) {
    //         $dateOnly = Carbon::parse($validatedData['selected_date'])->format('Y-m-d');
    //         $dayOfWeek = Carbon::parse($validatedData['selected_date'])->dayOfWeek;
    
    //         // Check if there are already 2 check-in and 2 check-out records for the employee on the selected date
    //         $checkInCount = EmployeeAttendanceTimeIn::where('employee_id', $employee_id)
    //             ->whereDate('check_in_time', $dateOnly)
    //             ->count();

    //         $checkOutCount = EmployeeAttendanceTimeOut::where('employee_id', $employee_id)
    //             ->whereDate('check_out_time', $dateOnly)
    //             ->count();

    //         // if ($checkInCount == 0 && $checkOutCount == 0) {
    //             $currentDayOfWeek = now()->dayOfWeek;

    //             // Retrieve the DepartmentWorkingHour record for the current department and day of the week
    //             $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $employees->department_id)
    //                 ->where('day_of_week', $dayOfWeek)
    //                 ->first();


    //             if($checkInCount == 0 && $checkOutCount == 0){

    //                 //morning & afternoon start time/time-in
    //                 $attendanceInAm = new EmployeeAttendanceTimeIn();
    //                 $attendanceInAm->employee_id = $employees->id;
    //                 if($departmentWorkingHour){
    //                     if($departmentWorkingHour->morning_start_time) {
    //                         $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_start_time;
    //                     } else {
    //                         $attendanceInAm->check_in_time = $validatedData['selected_date'];
    //                     } 
    //                 }else {
    //                     $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' 00:00:00';
    //                 }
    //                 $attendanceInAm->status = $validatedData['status'];
    //                 $attendanceInAm->save();

    //                  $attendanceOutAm = new EmployeeAttendanceTimeOut();
    //                 $attendanceOutAm->employee_id = $employees->id;
    //                 if($departmentWorkingHour){
    //                     if($departmentWorkingHour->morning_end_time){
    //                         $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_end_time;
    //                     } else {
    //                         $attendanceOutAm->check_out_time = $validatedData['selected_date'];
    //                     }
    //                 } else {
    //                     $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' 00:00:00';
    //                 }
    //                 $attendanceOutAm->status = $validatedData['status'];
    //                 $attendanceOutAm->save();

                

                
    //                 //morning & afternoon end time/time-out
    //                 $attendanceInAm = new EmployeeAttendanceTimeIn();
    //                 $attendanceInAm->employee_id = $employees->id;
    //                 if($departmentWorkingHour){
    //                     if($departmentWorkingHour->afternoon_start_time){
    //                         $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_start_time;
    //                     } else {
    //                         $attendanceInAm->check_in_time = $validatedData['selected_date'];
    //                     }
    //                 }else {
    //                     $attendanceInAm->check_in_time = $validatedData['selected_date'] . ' 00:00:00';
    //                 }
    //                 $attendanceInAm->status = $validatedData['status'];
    //                 $attendanceInAm->save();

    //                 $attendanceOutAm = new EmployeeAttendanceTimeOut();
    //                 $attendanceOutAm->employee_id = $employees->id;
    //                 if($departmentWorkingHour){
    //                     if($departmentWorkingHour->afternoon_end_time){
    //                         $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_end_time;
    //                     } else {
    //                         $attendanceOutAm->check_out_time = $validatedData['selected_date'];
    //                     }
                        
    //                 } else {
    //                     $attendanceOutAm->check_out_time = $validatedData['selected_date'] . ' 00:00:00';
    //                 }
    //                 $attendanceOutAm->status = $validatedData['status'];
    //                 $attendanceOutAm->save();

    //             } 


    //             if($checkInCount == 2 && $checkOutCount == 2){
    //                 return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Cannot modify date: check-in and check-out records exist.');
    //             }

    //             return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Date Successfully modified!');

    //     }

    //     return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Employee not found.');
    // }


    public function modifyAttendanceHalfDay(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'selected_date' => 'required|date',
            'status' => 'required|string',
            'employee_id' => 'required|integer',
            'day_of_week' => 'required|integer',
            'am_shift' => 'nullable|string',
            'pm_shift' => 'nullable|string',
        ]);

        // Convert checkbox values to boolean
        $validatedData['am_shift'] = $validatedData['am_shift'] === '1'; // Checkbox values might be '1' or '0'
        $validatedData['pm_shift'] = $validatedData['pm_shift'] === '1'; // Checkbox values might be '1' or '0'

        $employee_id = $validatedData['employee_id'];
        $employees = Employee::where('id', $employee_id)->first();

        if ($employees) {
            $dateOnly = Carbon::parse($validatedData['selected_date'])->format('Y-m-d');
            $dayOfWeek = Carbon::parse($validatedData['selected_date'])->dayOfWeek;

            // Check if there are already 2 check-in and 2 check-out records for the employee on the selected date
            $checkInCount = EmployeeAttendanceTimeIn::where('employee_id', $employee_id)
                ->whereDate('check_in_time', $dateOnly)
                ->count();

            $checkOutCount = EmployeeAttendanceTimeOut::where('employee_id', $employee_id)
                ->whereDate('check_out_time', $dateOnly)
                ->count();

            if ($checkInCount == 0 && $checkOutCount == 0) {
                $currentDayOfWeek = now()->dayOfWeek;

                // Retrieve the DepartmentWorkingHour record for the current department and day of the week
                $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $employees->department_id)
                    ->where('day_of_week', $dayOfWeek)
                    ->first();

                // Determine status for AM and PM shifts
                $selectedStatus = $validatedData['status'];
                $defaultStatus = 'On-Campus'; // Set default status

                if ($validatedData['am_shift'] && $validatedData['pm_shift']) {
                    return back()->with('error', 'Error! AM and PM are selected for half day leave');
                }
                // If only AM shift is selected, create PM attendance with default status
                if ($validatedData['am_shift'] && !$validatedData['pm_shift']) {
                    
                    $attendanceInPm = new EmployeeAttendanceTimeIn();
                    $attendanceInPm->employee_id = $employees->id;
                    $attendanceInPm->check_in_time = $departmentWorkingHour ? ($departmentWorkingHour->morning_start_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_start_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    $attendanceInPm->status = $selectedStatus;
                    $attendanceInPm->save();

                    $attendanceOutPm = new EmployeeAttendanceTimeOut();
                    $attendanceOutPm->employee_id = $employees->id;
                    $attendanceOutPm->check_out_time = $departmentWorkingHour ? ($departmentWorkingHour->morning_end_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_end_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    $attendanceOutPm->status = $selectedStatus;
                    $attendanceOutPm->save();

                    // $attendanceInAm = new EmployeeAttendanceTimeIn();
                    // $attendanceInAm->employee_id = $employees->id;
                    // $attendanceInAm->check_in_time = $departmentWorkingHour ? ($departmentWorkingHour->afternoon_start_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_start_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    // $attendanceInAm->status = $defaultStatus;
                    // $attendanceInAm->save();

                    // $attendanceOutAm = new EmployeeAttendanceTimeOut();
                    // $attendanceOutAm->employee_id = $employees->id;
                    // $attendanceOutAm->check_out_time = $departmentWorkingHour ? ($departmentWorkingHour->afternoon_end_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_end_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    // $attendanceOutAm->status = $defaultStatus;
                    // $attendanceOutAm->save();

                    


                }

                // If only PM shift is selected, create AM attendance with default status
                if (!$validatedData['am_shift'] && $validatedData['pm_shift']) {

                    // $attendanceInPm = new EmployeeAttendanceTimeIn();
                    // $attendanceInPm->employee_id = $employees->id;
                    // $attendanceInPm->check_in_time = $departmentWorkingHour ? ($departmentWorkingHour->morning_start_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_start_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    // $attendanceInPm->status = $defaultStatus;
                    // $attendanceInPm->save();

                    // $attendanceOutPm = new EmployeeAttendanceTimeOut();
                    // $attendanceOutPm->employee_id = $employees->id;
                    // $attendanceOutPm->check_out_time = $departmentWorkingHour ? ($departmentWorkingHour->morning_end_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->morning_end_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    // $attendanceOutPm->status = $defaultStatus;
                    // $attendanceOutPm->save();

                    $attendanceInAm = new EmployeeAttendanceTimeIn();
                    $attendanceInAm->employee_id = $employees->id;
                    $attendanceInAm->check_in_time = $departmentWorkingHour ? ($departmentWorkingHour->afternoon_start_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_start_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    $attendanceInAm->status = $selectedStatus;
                    $attendanceInAm->save();

                    $attendanceOutAm = new EmployeeAttendanceTimeOut();
                    $attendanceOutAm->employee_id = $employees->id;
                    $attendanceOutAm->check_out_time = $departmentWorkingHour ? ($departmentWorkingHour->afternoon_end_time ? $validatedData['selected_date'] . ' ' . $departmentWorkingHour->afternoon_end_time : $validatedData['selected_date']) : $validatedData['selected_date'] . ' 00:00:00';
                    $attendanceOutAm->status = $selectedStatus;
                    $attendanceOutAm->save();


                    
                }

            } elseif ($checkInCount == 2 && $checkOutCount == 2) {
                return back()->with('error', 'Cannot modify date: check-in and check-out records exist.');
            }

            //return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Date Successfully modified!');
            return back()->with('success', 'Half Day Leave Successfully Added!');
        }

        return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Employee not found.');
    }



    public function modifyAttendanceAbsent(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'selected_date' => 'required|date',
            'status' => 'required|string',
            'employee_id' => 'required|integer',
            'day_of_week' => 'required|integer'
        ]);
 

        // // Convert checkbox values to boolean
        // $validatedData['am_shift'] = $validatedData['am_shift'] === '1'; // Checkbox values might be '1' or '0'
        // $validatedData['pm_shift'] = $validatedData['pm_shift'] === '1'; // Checkbox values might be '1' or '0'

        $employee_id = $validatedData['employee_id'];
        $employees = Employee::where('id', $employee_id)->first();

        if ($employees) {
            $dateOnly = Carbon::parse($validatedData['selected_date'])->format('Y-m-d');
            $dayOfWeek = Carbon::parse($validatedData['selected_date'])->dayOfWeek;

            // Check if there are already 2 check-in and 2 check-out records for the employee on the selected date
            $checkInCount = EmployeeAttendanceTimeIn::where('employee_id', $employee_id)
                ->whereDate('check_in_time', $dateOnly)
                ->count();

            $checkOutCount = EmployeeAttendanceTimeOut::where('employee_id', $employee_id)
                ->whereDate('check_out_time', $dateOnly)
                ->count();

            if ($checkInCount == 0 && $checkOutCount == 0) {
                $currentDayOfWeek = now()->dayOfWeek;

                $attendanceIn = new EmployeeAttendanceTimeIn();
                $attendanceIn->employee_id = $employee_id;
                $attendanceIn->check_in_time = $validatedData['selected_date'] . ' 00:00:00';
                $attendanceIn->status = $validatedData['status'];
                $attendanceIn->save();

                $attendanceIn = new EmployeeAttendanceTimeIn();
                $attendanceIn->employee_id = $employee_id;
                $attendanceIn->check_in_time = $validatedData['selected_date'] . ' 00:00:00';
                $attendanceIn->status = $validatedData['status'];
                $attendanceIn->save();

                $attendance = new EmployeeAttendanceTimeOut();
                $attendance->employee_id = $employee_id;
                $attendance->check_out_time = $validatedData['selected_date'] . ' 00:00:00';
                $attendance->status = $validatedData['status'];
                $attendance->save();

                $attendance = new EmployeeAttendanceTimeOut();
                $attendance->employee_id = $employee_id;
                $attendance->check_out_time = $validatedData['selected_date'] . ' 00:00:00';
                $attendance->status = $validatedData['status'];
                $attendance->save();

                
            } else {
                return back()->with('error', 'Date exist!');
            }
            //return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Date Successfully modified!');
            return back()->with('success', ' Date successfully modified');
        }

        return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Employee not found.');
    }




    public function attendanceTimeInUpdate(Request $request, string $id){
            
        // Validate the request data
        $validatedData = $request->validate([
            'attendanceIn_id' => 'required',
            'check_in_time_date' => 'required|date',
            'check_in_time_time' => 'required|date_format:H:i',
        ]);

        $date = $validatedData['check_in_time_date'];
        $time = $validatedData['check_in_time_time'];

        // Combine date and time into a single string
        $dateTimeString = "{$date} {$time}";

        // Convert the date and time into a Carbon instance
        try {
            $checkInTime = Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);
            // Debugging output


            // Format the time for storage
            $formattedTime = $checkInTime->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Handle invalid time format
            return back()->with('error', 'Invalid time format.');
            //return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Invalid time format.');
        }

        // Find the record by ID
        $attendanceIn = EmployeeAttendanceTimeIn::findOrFail($id);

        // Check if the value has changed
        if ($attendanceIn->check_in_time === $formattedTime) {
            // No changes made
            return back()->with('info', 'No changes made.');
            //return redirect()->route('admin.attendance.employee_attendance')->with('info', 'No changes made.');
        }

        // Update the check-in time and modification status
        $attendanceIn->check_in_time = $formattedTime;
        $attendanceIn->status = 'On-campus';
        $attendanceIn->modification_status = 'modified';
        $attendanceIn->save();

        // Redirect or return a response
        return back()->with('success', 'Time In updated successfully!');
        // return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Time In updated successfully!');
    }



    public function attendanceTimeOutUpdate(Request $request, string $id){
            
        // Validate the request data
        $validatedData = $request->validate([
            'attendanceOut_id' => 'required',
            'check_out_time_date' => 'required|date',
            'check_out_time_time' => 'required|date_format:H:i',
        ]);



        $date = $validatedData['check_out_time_date'];
        $time = $validatedData['check_out_time_time'];

        // Combine date and time into a single string
        $dateTimeString = "{$date} {$time}";

        // Convert the date and time into a Carbon instance
        try {
            $checkOutTime = Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);
            // Debugging output


            // Format the time for storage
            $formattedTime = $checkOutTime->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Handle invalid time format
            return back()->with('error', 'Invalid time format.');
            //return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Invalid time format.');
            
        }

        // Find the record by ID
        $attendanceOut = EmployeeAttendanceTimeOut::findOrFail($id);

        // Check if the value has changed
        if ($attendanceOut->check_out_time === $formattedTime) {
            // No changes made
            //return redirect()->route('admin.attendance.employee_attendance')->with('info', 'No changes made.');
            return back()->with('info', 'No changes made.');
        }

        // Update the check-in time and modification status
        $attendanceOut->check_out_time = $formattedTime;
        $attendanceOut->status = 'Off-campus';
        $attendanceOut->modification_status = 'modified';
        $attendanceOut->save();

        // Redirect or return a response
        return back()->with('success', 'Time Out updated successfully!');
        //return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Time Out updated successfully!');
    }



    public function employeeAddTimeIn(Request $request)
    {

        // Validate the request
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id', // Ensure employee_id exists
            'selected-date-time' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Convert the validated date-time value to a Carbon instance
        $selectedDateTime = Carbon::parse($validatedData['selected-date-time']) ;
        $formattedDateTime = $selectedDateTime->format('Y-m-d H:i:s');
        $dateOnly = $selectedDateTime->toDateString();
        
        // Check if there are already 2 check-in records for the employee
        $checkInCount = EmployeeAttendanceTimeIn::where('employee_id', $validatedData['employee_id'])
            ->whereDate('check_in_time', $dateOnly) // Optional: Check for today's date
            ->count();

        // Insert the new record if the count is less than 2
        if ($checkInCount < 2) {
            EmployeeAttendanceTimeIn::create([
                'employee_id' => $validatedData['employee_id'],
                'check_in_time' => $formattedDateTime,
                'status' => "On-campus",
                // Add other required fields here
            ]);
            
            return back()->with('success', 'Time In recorded successfully!');
            //return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Time In recorded successfully!');
            // return response()->json(['message' => 'Check-in time recorded successfully.']);
        } else {
            return back()->with('error', 'Cannot record more than 2 check-in times');
            //return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Cannot record more than 2 check-in times');
            // return response()->json(['message' => 'Cannot record more than 2 check-in times for today.'], 400);
        }


    }


    public function employeeAddTimeOut(Request $request)
    {

        // Validate the request
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id', // Ensure employee_id exists
            'selected-date-time' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Convert the validated date-time value to a Carbon instance
        $selectedDateTime = Carbon::parse($validatedData['selected-date-time']) ;
        $formattedDateTime = $selectedDateTime->format('Y-m-d H:i:s');
        $dateOnly = $selectedDateTime->toDateString();
        
        // Check if there are already 2 check-in records for the employee
        $checkInCount = EmployeeAttendanceTimeOut::where('employee_id', $validatedData['employee_id'])
            ->whereDate('check_out_time', $dateOnly) // Optional: Check for today's date
            ->count();

        // Insert the new record if the count is less than 2
        if ($checkInCount < 2) {
            EmployeeAttendanceTimeOut::create([
                'employee_id' => $validatedData['employee_id'],
                'check_out_time' => $formattedDateTime,
                'status' => "On-campus",
                // Add other required fields here
            ]);
            
            // return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Time Out recorded successfully!');
             return back()->with('success', 'Time Out recorded successfully!');
            // return response()->json(['message' => 'Check-in time recorded successfully.']);
        } else {
            // return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Cannot record more than 2 check-out times');
            return back()->with('error', 'Cannot record more than 2 check-out times');
            // return response()->json(['message' => 'Cannot record more than 2 check-in times for today.'], 400);
        }


    }



    public function deleteTimeIn($id)
    {

        $attendanceIn = EmployeeAttendanceTimeIn::find($id);

        if ($attendanceIn) {

            $attendanceIn->delete();

            // return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Attendance deleted successfully.');
             return back()->with('success', 'Attendance deleted successfully.');
        } else {
            // return redirect()->route('admin.attendance.employee_attendance')->with('error', 'Attendance record not found.');
            return back()->with('error', 'Attendance record not found.');
        }

    }

    public function deleteTimeOut($id)
    {

        $attendanceOut = EmployeeAttendanceTimeOut::find($id);

        if ($attendanceOut) {

            $attendanceOut->delete();

            // return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Attendance deleted successfully.');
            return back()->with('success', 'Attendance deleted successfully.');
        } else {
             return back()->with('error', 'Attendance record not found.');
        }

    }


    public function storePeriod(Request $request)
    {
        $validatedData = $request->validate([
            'grace_period' => 'required|integer',
        ]);

        $gracePeriodInSeconds = ($validatedData['grace_period'] * 60); // Convert minutes to seconds

        // Convert total seconds to fractional hours
        $totalHours = $gracePeriodInSeconds / 3600;

        // Format the result to two decimal places
        $formattedHours = number_format($totalHours, 2);

        // Check if there is already a grace period in the database
        $existingGracePeriodCount = GracePeriod::count();

        if ($existingGracePeriodCount >= 1) {
            return back()->with('error', 'A grace period has already been added. You cannot add another one.');
        }

        // If no existing grace period, proceed to add a new one
        $gracePeriod = new GracePeriod();
        $gracePeriod->grace_period = $formattedHours;
        $gracePeriod->save();

        return back()->with('success', 'Grace Period successfully added!');

    }

    public function updatePeriod(Request $request, $id)
    {
        $validatedData = $request->validate([
            'grace_period' => 'required|integer',
        ]);

        $gracePeriodInSeconds = ($validatedData['grace_period'] * 60); // Convert minutes to seconds

        // Convert total seconds to fractional hours
        $totalHours = $gracePeriodInSeconds / 3600;

        // Format the result to two decimal places
        $formattedHours = number_format($totalHours, 2);
        $gracePeriod = GracePeriod::find($id);
        $gracePeriod->grace_period = $formattedHours;
        $gracePeriod->save();

        return back()->with('success', 'Grace Period successfully updated!');

    }
    

    public function deletePeriod($id)
    {
        $gracePeriod = GracePeriod::find($id);

        if ($gracePeriod) {

            $gracePeriod->delete();

            // return redirect()->route('admin.attendance.employee_attendance')->with('success', 'Attendance deleted successfully.');
            return back()->with('success', 'Grace Period deleted successfully.');
        } else {
             return back()->with('error', 'Grace Period record not found.');
        }
    }


    public function storePeriodView()
    {
        $gracePeriod = GracePeriod::all();
        return view('Admin.graceperiod.index', compact('gracePeriod'));
    }

    public function holiday()
    {
        // Fetch the authenticated user's school_id
        $schoolId = Auth::user()->school_id;
        
        // Fetch holidays only for the authenticated user's school
        $holidays = EmployeeAttendanceTimeIn::select(
                            DB::raw('DATE(check_in_time) as check_in_date'),
                            'holiday_description',
                            DB::raw('MIN(id) as id'), // Ensure a unique ID for each group
                            DB::raw('MIN(employee_id) as employee_id') // Pick one employee_id per group
                        )
                        ->where('status', 'Holiday')
                        ->whereHas('employee.school', function($query) use ($schoolId) {
                            $query->where('id', $schoolId); // Filter holidays by school_id
                        })
                        ->groupBy(DB::raw('DATE(check_in_time)'), 'holiday_description') // Group by the extracted date and holiday description
                        ->orderBy('check_in_date', 'desc') // Order by the extracted date
                        ->get();


  
        // Count the number of unique holiday dates for the authenticated user's school
        $holidayCount = EmployeeAttendanceTimeIn::where('status', 'Holiday')
                        ->select(DB::raw('DATE(check_in_time) as check_in_date')) // Extract only the date
                        ->whereHas('employee.school', function($query) use ($schoolId) {
                            $query->where('id', $schoolId); // Filter holidays by school_id
                        })
                        ->groupBy('check_in_date') // Group by the extracted date
                        ->get()
                        ->count(); // Count the number of unique dates

 
        return view('Admin.holiday.index', compact('holidays', 'holidayCount'));
    }



    public function setHoliday(Request $request)
    {
        // Validate the selected date
        $validatedData = $request->validate([
            'selected_date' => 'required|date',
            'holiday_description' => 'required|string|max:255'
        ]);

        // Set the current date from the validated data
        $currentDate = $validatedData['selected_date'];
        $dayOfWeek = \Carbon\Carbon::parse($currentDate)->dayOfWeek;
        
        // Retrieve all employees
        $employees = Employee::all();
    
        foreach ($employees as $employee) {

            $employeeId = $employee->id;
            $departmentId = $employee->department_id;

            // // Fetch the working hour for the department on the selected day
            // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $departmentId)
            //     ->where('day_of_week', $dayOfWeek)  
            //     ->first();



            // // Check if the working hour record exists
            // if (!$departmentWorkingHour) {
            //     // If no working hour is found, skip to the next employee
            //     continue;
            // }

            // Check if there are check-in or check-out records for the current date
            $hasCheckIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $currentDate)
                ->where('employee_id', $employeeId)
                ->exists();

            $hasCheckOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $currentDate)
                ->where('employee_id', $employeeId)
                ->exists();
                

            // Determine the status based on the day of the week
            $status = "Holiday";

            if ($hasCheckIn && $hasCheckOut) {
                return back()->with('error', 'Cannot modify the date to a holiday because it already exists.');
            }


            // Create missing check-in record if none exists
            if (!$hasCheckIn) {
                $attendance = new EmployeeAttendanceTimeIn();
                $attendance->employee_id = $employeeId;
                // $attendance->check_in_time = "{$currentDate} {$departmentWorkingHour->morning_start_time}";
                $attendance->check_in_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->holiday_description = $validatedData['holiday_description'];
                $attendance->save();


                

                // Duplicate record creation as per your request
                $attendance = new EmployeeAttendanceTimeIn();
                $attendance->employee_id = $employeeId;
                // $attendance->check_in_time = "{$currentDate} {$departmentWorkingHour->afternoon_start_time}";
                $attendance->check_in_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->holiday_description = $validatedData['holiday_description'];
                $attendance->save();
                
            }

            // Create missing check-out record if none exists
            if (!$hasCheckOut) {
                $attendance = new EmployeeAttendanceTimeOut();
                $attendance->employee_id = $employeeId;
                // $attendance->check_out_time = "{$currentDate} {$departmentWorkingHour->morning_end_time}";
                $attendance->check_out_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->holiday_description = $validatedData['holiday_description'];
                $attendance->save();

                // Duplicate record creation as per your request
                $attendance = new EmployeeAttendanceTimeOut();
                $attendance->employee_id = $employeeId;
                // $attendance->check_out_time = "{$currentDate} {$departmentWorkingHour->afternoon_end_time}";
                $attendance->check_out_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->holiday_description = $validatedData['holiday_description'];
                $attendance->save();
            }
        }


        return back()->with('success', 'Holiday date implemented to attendances.');
    }

    public function deleteHoliday($id)
    {
        $holidayIn = EmployeeAttendanceTimeIn::find($id);

        if ($holidayIn) {
            $dateToFind = Carbon::parse($holidayIn->check_in_time)->toDateString();

            // Delete related check-in attendance
            $attendanceTimeInRecords = EmployeeAttendanceTimeIn::whereDate('check_in_time', $dateToFind)->get();
            $attendanceTimeOutRecords = EmployeeAttendanceTimeOut::whereDate('check_out_time', $dateToFind)->get();
            
            foreach ($attendanceTimeInRecords as $record) {
                $record->delete();
            }

            
            foreach ($attendanceTimeOutRecords as $record) {
                $record->delete();
            }
           

            return back()->with('success', 'Holiday and associated attendance records deleted successfully.');
        }

        return back()->with('error', 'Holiday record not found.');
    }

    public function validate_delete_attendance(Request $request)
    {
        $request->validate([
            'selected_date' => 'required|date',
        ]);

        $selectedDate = $request->input('selected_date');

        // Delete attendance records for the selected date

        $employee_in = EmployeeAttendanceTimeIn::whereDate('check_in_time', $selectedDate)->get();
        $employee_out = EmployeeAttendanceTimeOut::whereDate('check_out_time', $selectedDate)->get();

        $in_count = count($employee_in);
        $out_count = count($employee_out);

        if(($in_count == 0) && ($out_count == 0))
        {
            return back()->with('info', 'No attendance recorded in this date!');
        } 
        else 
        {
            EmployeeAttendanceTimeIn::whereDate('check_in_time', $selectedDate)->delete();
            EmployeeAttendanceTimeOut::whereDate('check_out_time', $selectedDate)->delete();
        }

        return back()->with('success', 'Attendance successfully deleted!');
    }



}
