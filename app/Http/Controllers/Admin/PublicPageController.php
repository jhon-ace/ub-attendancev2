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
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use \App\Models\Admin\Fingerprint;
use \App\Models\Admin\ImageHandler;
use Illuminate\Support\Facades\Cache;

class PublicPageController extends Controller
{

    public function fetchLatestEmployeeUB()
    {
        $current_date = now(); // Get the current date

        $curdateDataIn = EmployeeAttendanceTimeIn::with('employee.department')
            ->whereDate('check_in_time', $current_date) // Filter by the current date
            ->whereHas('employee.department', function($query) {
                // Exclude departments with 'VDT' in the abbreviation
                $query->where('department_abbreviation', 'NOT LIKE', '%VDT%');
            })
            
            ->whereIn('status', ['On-campus'])
            ->orderBy('check_in_time', 'asc') // Order by check_in_time ascending
            ->get();


        $curdateDataOut = EmployeeAttendanceTimeOut::with('employee.department')
            ->whereDate('check_out_time', $current_date) // Filter by the current date
            ->whereHas('employee.department', function($query) {
                // Exclude departments with 'VDT' in the abbreviation
                $query->where('department_abbreviation', 'NOT LIKE', '%VDT%');
            })
            
            ->whereIn('status', ['Outside Campus'])
            ->orderBy('check_out_time', 'asc') // Order by check_out_time ascending
            ->get();

        $this->addProfileImagesEmployeeUB($curdateDataIn);
        $this->addProfileImagesEmployeeUB($curdateDataOut);

        // Retrieve image handler
        $imageHandler = ImageHandler::first();

        // Return the result as a JSON response
        return response()->json([
            'curdateDataIn' => $curdateDataIn,
            'imageHandler' => $imageHandler,
            'curdateDataOut' => $curdateDataOut
        ]);
    }


    // Reusable method to add profile image URLs
    private function addProfileImagesEmployeeUB($data)
    {
        $data->each(function ($record) {
            if ($record->employee) {
                $record->employee->profile_image = $record->employee->employee_photo
                    ? asset('storage/employee_photo/' . $record->employee->employee_photo)
                    : asset('assets/img/user.png');
            }
        });
    }

    public function portalTimeIn()
    {

        $adminUser = User::where('role', 'admin')->first();

        if ($adminUser) {
            $timezone = 'Asia/Taipei';

            $current_date1 = Carbon::now($timezone)->format('D, M d, Y'); // E.g., "Mon, Jul 30, 2024"
            $current_time1 = Carbon::now($timezone)->format('h:i:s A');    
            return view('attendance_time_in', compact('current_time1', 'current_date1'));
        }

        return redirect()->back()->with('error', 'No admin user found.');
    }

   

    public function submitAttendance(Request $request)
    {
        $fingerprint = Fingerprint::first();
        $fingerprintStatus = $fingerprint->fingerprint_status;

        $request->validate([
            'user_rfid' => 'required|string|max:255',
        ]);

            $adminUser = User::where('role', 'admin')->first();

            if($adminUser)
            {
                if($fingerprintStatus == 0) //if zero, fingerprint is not required!
                {

                
                    $rfid = $request->input('user_rfid');
                    
                    // Query to get the employee based on the RFID
                    $employee = Employee::where('employee_rfid', $rfid)->first();
                    // Query to get the employee based on the RFID for compact
                    $employees = Employee::where('employee_rfid', $rfid)->get();

                    $imageHandler = ImageHandler::first();

                        
                    if ($employee) {
                        // Get the current datetime in Kuala Lumpur timezone
                        // $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
                        // $now = new DateTime('now', new DateTimeZone('Asia/Taipei'));


                        // if (stripos($employee->department->department_abbreviation, 'VDT') !== false) {
                        //     // If the department abbreviation contains 'VDT', deny attendance
                        //     return redirect()->route('attendance.portal')->with('error', 'You\'re from VDT. Attendance Invalid.');
                        // }


                        $now = Carbon::now('Asia/Taipei');
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
                            $checkInTime = new DateTime($firstTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
                            $interval = $now->diff($checkInTime);
                            $minutes = $interval->i + ($interval->h * 60);
                            if ($minutes >= 45) {
                                $intervalAllowed = true;
                            }
                        }

                        // Check if the employee has already checked out in the afternoon
                        if ($timeInCount == 2 && $timeOutCount == 2) {

                            // return redirect()->route('attendance.portal')->with('success', 'Attendance completed. Safe travels home!');
                            return response()->json(['success' => true, 'message' => 'Attendance completed. Safe travels home !!']);
                            // return response()->json([
                            //     'message' => 'Already checked out in the afternoon. Go home safely!',
                            // ], 403);
                        }
                        else if ($timeInCount == 1 && $timeOutCount == 1) {
                                
                            $intervalAllowed = false;

                            if ($firstTimeOut) {
                                $checkOutTime = new DateTime($firstTimeOut->check_out_time, new DateTimeZone('Asia/Taipei'));
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
                                $attendanceIn->save();

                                $first_time_in = $attendanceIn->check_in_time = $formattedDateTime;

                                if ($imageHandler) {
                                     $imageHandler->value = 0;
                                     $imageHandler->save();
                                 }
                                // return response()->json([
                                //     'message' => 'PM Time-in recorded successfully.',
                                //     'employee' => $employee,
                                //     'check_in_time' => $formattedDateTime,
                                // ], 200);
                                // return view('attendance-profile_time_in_employee', compact('employees', 'first_time_in'));
                                return response()->json(['success' => true, 'message' => 'Time-In Attendance recorded successfully']);
                            } else {
                                // return response()->json([
                                //     'message' => 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out.',
                                // ], 403);
                                // return redirect()->route('admin.attendance.time-in.portal')->with('error', 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out!');
                                // return redirect()->route('attendance.portal')->with('error', 'Please wait 45 minutes to time in!');
                                return response()->json(['error' => true, 'message' => 'Please wait 45 minutes to time in!']);
                            }

                        } elseif ($timeInCount == 1 && $timeOutCount == 0) {
                            
                            if ($intervalAllowed) 
                            {

                                // First check-out (AM)
                                $attendanceOut = new EmployeeAttendanceTimeOut();
                                $attendanceOut->employee_id = $employee->id;
                                $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
                                $attendanceOut->status = "Outside Campus";
                                $attendanceOut->save();

                                $first_time_out = $attendanceOut->check_out_time = $formattedDateTime;

                                 if ($imageHandler) {
                                     $imageHandler->value = 1;
                                     $imageHandler->save();
                                 }
                                // return response()->json([
                                //     'message' => 'AM Time-out recorded successfully.',
                                //     'employee' => $employee,
                                //     'check_out_time' => $formattedDateTime,
                                // ], 200);
                                // return view('attendance-profile_time_out_employee', compact('employees', 'first_time_out'));
                                // return response()->json(['success' => true, 'message' => 'Attendance Recorded']);
                                return response()->json(['success' => true, 'message' => 'Time-Out Attendance recorded successfully']);
                            } else {
                                // return response()->json([
                                //     'message' => 'Already Time In Morning.',
                                // ], 403);
                                // return redirect()->route('attendance.portal')->with('success', 'Already Timed In!');
                                return response()->json(['success' => true, 'message' => 'Already Timed In!']);
                            }

                        } elseif ($timeInCount == 2 && $timeOutCount == 1) {
                                // Check interval for second check-out
                            $intervalAllowed = false;
                            if ($lastTimeIn) {
                                $checkInTime = new DateTime($lastTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
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
                                $attendanceOut->save();

                                $first_time_out = $attendanceOut->check_out_time = $formattedDateTime;

                                if ($imageHandler) {
                                     $imageHandler->value = 1;
                                     $imageHandler->save();
                                 }
                                // return response()->json([
                                //     'message' => 'PM Time-out recorded successfully.',
                                //     'employee' => $employee,
                                //     'check_out_time' => $formattedDateTime,
                                // ], 200);
                                // return view('attendance-profile_time_out_employee', compact('employees', 'first_time_out'));
                                // return response()->json(['success' => true, 'message' => 'Attendance Recorded']);
                                return response()->json(['success' => true, 'message' => 'Time-Out Attendance recorded successfully']);
                            } else {
                                // return response()->json([
                                //     'message' => 'Already Time-in Afternoon.',
                                // ], 403);
                                // return redirect()->route('attendance.portal')->with('success', 'Already Timed In!');
                                return response()->json(['success' => true, 'message' => 'Already Timed In!']);
                            }

                        } else {
                            // First time-in (AM)
                            $attendanceIn = new EmployeeAttendanceTimeIn();
                            $attendanceIn->employee_id = $employee->id;
                            $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
                            $attendanceIn->status = "On-campus";
                            $attendanceIn->save();

                            $first_time_in = $attendanceIn->check_in_time = $formattedDateTime;

                            if ($imageHandler) {
                                     $imageHandler->value = 0;
                                     $imageHandler->save();
                                 }
                            

                            // return view('attendance-profile_time_in_employee', compact('employees','first_time_in'));
                            // return response()->json(['success' => true, 'message' => 'Attendance Recorded']);
                            return response()->json(['success' => true, 'message' => 'Time-In Attendance recorded successfully']);
                            // return response()->json([
                            //     'message' => 'AM Time-in recorded successfully.',
                            //     'employee' => $employee,
                            //     'check_in_time' => $formattedDateTime,
                            // ], 200);
                        }
                    } else {
                        // Handle case where employee with given RFID is not found
                        // return response()->json(['error' => 'Employee not found.'], 404);
                        // return redirect()->route('attendance.portal')->with('error', 'RFID not Recognized!');
                        return response()->json(['error' => true, 'message' => 'RFID not Recognized!']);
                    }









                } else { // fingerprint is 1 means fingerprint after RFID scan is required

                    $rfid = $request->input('user_rfid');
                    
                    // Query to get the employee based on the RFID
                    $employee = Employee::where('employee_rfid', $rfid)->first();
                    // Query to get the employee based on the RFID for compact
                    $employees = Employee::where('employee_rfid', $rfid)->get();
                        
                    if ($employee) {
                        // Get the current datetime in Kuala Lumpur timezone
                        // $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
                        // $now = new DateTime('now', new DateTimeZone('Asia/Taipei'));


                        // if (stripos($employee->department->department_abbreviation, 'VDT') !== false) {
                        //     // If the department abbreviation contains 'VDT', deny attendance
                        //     return redirect()->route('attendance.portal')->with('error', 'You\'re from VDT. Attendance Invalid.');
                        // }


                        $now = Carbon::now('Asia/Taipei');
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
                            $checkInTime = new DateTime($firstTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
                            $interval = $now->diff($checkInTime);
                            $minutes = $interval->i + ($interval->h * 60);
                            if ($minutes >= 45) {
                                $intervalAllowed = true;
                            }
                        }

                        // Check if the employee has already checked out in the afternoon
                        if ($timeInCount == 2 && $timeOutCount == 2) {

                            // return redirect()->route('attendance.portal')->with('success', 'Attendance completed. Safe travels home!');
                            return response()->json(['success' => true, 'message' => 'Attendance completed. Safe travels home!']);
                            // return response()->json([
                            //     'message' => 'Already checked out in the afternoon. Go home safely!',
                            // ], 403);
                        }
                        else if ($timeInCount == 1 && $timeOutCount == 1) {
                                
                            $intervalAllowed = false;

                            if ($firstTimeOut) {
                                $checkOutTime = new DateTime($firstTimeOut->check_out_time, new DateTimeZone('Asia/Taipei'));
                                $interval = $now->diff($checkOutTime);
                                $minutes = $interval->i + ($interval->h * 60);
                                if ($minutes >= 45) {
                                    $intervalAllowed = true;
                                }
                            }

                            if ($intervalAllowed) 
                            {
                                dd("fingerprint required");
                                // Second time-in (PM), no second check-out
                                $attendanceIn = new EmployeeAttendanceTimeIn();
                                $attendanceIn->employee_id = $employee->id;
                                $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
                                $attendanceIn->status = "On-campus";
                                $attendanceIn->save();

                                $first_time_in = $attendanceIn->check_in_time = $formattedDateTime;
                                // return response()->json([
                                //     'message' => 'PM Time-in recorded successfully.',
                                //     'employee' => $employee,
                                //     'check_in_time' => $formattedDateTime,
                                // ], 200);
                                // return view('attendance-profile_time_in_employee', compact('employees', 'first_time_in'));
                                return response()->json(['success' => true, 'message' => 'Time-In Attendance recorded successfully']);
                            } else {
                                // return response()->json([
                                //     'message' => 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out.',
                                // ], 403);
                                // return redirect()->route('admin.attendance.time-in.portal')->with('error', 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out!');
                                // return redirect()->route('attendance.portal')->with('error', 'Please wait 45 minutes to time in!');
                                return response()->json(['error' => true, 'message' => 'Please wait 45 minutes to time in!']);
                            }

                        } elseif ($timeInCount == 1 && $timeOutCount == 0) {
                            
                            if ($intervalAllowed) 
                            {
                                dd("fingerprint required");
                                // First check-out (AM)
                                $attendanceOut = new EmployeeAttendanceTimeOut();
                                $attendanceOut->employee_id = $employee->id;
                                $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
                                $attendanceOut->status = "Outside Campus";
                                $attendanceOut->save();

                                $first_time_out = $attendanceOut->check_out_time = $formattedDateTime;
                                // return response()->json([
                                //     'message' => 'AM Time-out recorded successfully.',
                                //     'employee' => $employee,
                                //     'check_out_time' => $formattedDateTime,
                                // ], 200);
                                // return view('attendance-profile_time_out_employee', compact('employees', 'first_time_out'));
                                return response()->json(['success' => true, 'message' => 'Time-Out Attendance recorded successfully']);
                            } else {
                                // return response()->json([
                                //     'message' => 'Already Time In Morning.',
                                // ], 403);
                                // return redirect()->route('attendance.portal')->with('success', 'Already Timed In!');
                                return response()->json(['success' => true, 'message' => 'Already Timed In!']);
                            }

                        } elseif ($timeInCount == 2 && $timeOutCount == 1) {
                                // Check interval for second check-out
                            $intervalAllowed = false;
                            if ($lastTimeIn) {
                                $checkInTime = new DateTime($lastTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
                                $interval = $now->diff($checkInTime);
                                $minutes = $interval->i + ($interval->h * 60);
                                if ($minutes >= 45) {
                                    $intervalAllowed = true;
                                }
                            }

                            if ($intervalAllowed) {
                                
                                dd("fingerprint required");
                                // Second check-out (PM)
                                $attendanceOut = new EmployeeAttendanceTimeOut();
                                $attendanceOut->employee_id = $employee->id;
                                $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
                                $attendanceOut->status = "Outside Campus";
                                $attendanceOut->save();

                                $first_time_out = $attendanceOut->check_out_time = $formattedDateTime;
                                // return response()->json([
                                //     'message' => 'PM Time-out recorded successfully.',
                                //     'employee' => $employee,
                                //     'check_out_time' => $formattedDateTime,
                                // ], 200);
                                // return view('attendance-profile_time_out_employee', compact('employees', 'first_time_out'));
                                return response()->json(['success' => true, 'message' => 'Time-Out Attendance recorded successfully']);
                            } else {
                                // return response()->json([
                                //     'message' => 'Already Time-in Afternoon.',
                                // ], 403);
                                // return redirect()->route('attendance.portal')->with('success', 'Already Timed In!');
                                return response()->json(['success' => true, 'message' => 'Already Timed In!']);
                                
                            }

                        } else {

                            dd("fingerprint required");
                            // First time-in (AM)
                            $attendanceIn = new EmployeeAttendanceTimeIn();
                            $attendanceIn->employee_id = $employee->id;
                            $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
                            $attendanceIn->status = "On-campus";
                            $attendanceIn->save();

                            $first_time_in = $attendanceIn->check_in_time = $formattedDateTime;

                            

                            // return view('attendance-profile_time_in_employee', compact('employees','first_time_in'));
                            return response()->json(['success' => true, 'message' => 'Time-In Attendance recorded successfully']);
                            // return response()->json([
                            //     'message' => 'AM Time-in recorded successfully.',
                            //     'employee' => $employee,
                            //     'check_in_time' => $formattedDateTime,
                            // ], 200);
                        }
                    } else {
                        // Handle case where employee with given RFID is not found
                        // return response()->json(['error' => 'Employee not found.'], 404);
                        // return redirect()->route('attendance.portal')->with('error', 'RFID not Recognized!');
                        return response()->json(['error' => true, 'message' => 'RFID not Recognized!']);
                    }

                }
            } else {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }
       
    }









    //  ------------------------------- VDT ------------------------------------------- //


     public function portalTimeInvdt()
    {   

            // Retrieve any user with the role 'admin'
            $adminUser = User::where('role', 'admin')->first();

            // Check if there is an admin user
            if ($adminUser) {
                // $current_date = now()->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d');
                $current_date = now()->setTimezone('Asia/Taipei')->format('Y-m-d');
                $timezone = 'Asia/Taipei';
                $current_date1 = Carbon::now($timezone)->format('D, M d, Y'); // E.g., "Mon, Jul 30, 2024"
                $current_time1 = Carbon::now($timezone)->format('h:i:s A'); 

                // Retrieve attendance data for the current date with department_abbreviation containing 'VDT'
                // Retrieve attendance data for the current date with department_abbreviation containing 'VDT'
                $curdateDataIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $current_date)
                    ->whereHas('employee.department', function($query) {
                        $query->where('department_abbreviation', 'LIKE', '%VDT%');
                    })
                    ->whereNotIn('status', ['On Leave', 'Official Travel', 'Holiday'])
                    ->orderBy('check_in_time', 'asc') // Order by check_in_time in descending order
                    ->get();

                $curdateDataOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $current_date)
                    ->whereHas('employee.department', function($query) {
                        $query->where('department_abbreviation', 'LIKE', '%VDT%');
                    })
                    ->whereNotIn('status', ['On Leave', 'Official Travel', 'Holiday'])
                    ->orderBy('check_out_time', 'asc') // Order by check_out_time in descending order
                    ->get();


                // Return view with the attendance data
                return view('attendance_time_in_vdt', compact('curdateDataIn', 'curdateDataOut', 'current_time1', 'current_date1'));
            }

            // Redirect with an error message if no admin user is found
            return redirect()->back()->with('error', 'No admin user found.');
            
            // // Retrieve any user with the role 'admin'
            // $adminUser = User::where('role', 'admin')->first();

            // // Check if there is an admin user
            // if ($adminUser) {
            //     // $current_date = now()->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d');
            //     $current_date = now()->setTimezone('Asia/Taipei')->format('Y-m-d');
            //     $current_time = now()->setTimezone('Asia/Taipei')->format('H:i:s');

            //        $timezone = 'Asia/Taipei';

            //     $current_date1 = Carbon::now($timezone)->format('D, M d, Y'); // E.g., "Mon, Jul 30, 2024"
            //     $current_time1 = Carbon::now($timezone)->format('h:i:s A'); 

            //     // Retrieve attendance data for the current date
            //     $curdateDataIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $current_date)->get();
            //     $curdateDataOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $current_date)->get();

            //     // Return view with the attendance data
            //     return view('attendance_time_in_vdt', compact('curdateDataIn', 'curdateDataOut','current_time1', 'current_date1'));
            // }

            // // Redirect with an error message if no admin user is found
            // return redirect()->back()->with('error', 'No admin user found.');
    }

    public function submitAttendancevdt(Request $request)
    {
        $request->validate([
            'user_rfid' => 'required|string|max:255',
        ]);

            $adminUser = User::where('role', 'admin')->first();
            
            if($adminUser)
            {
                $rfid = $request->input('user_rfid');
                
                // Query to get the employee based on the RFID
                $employee = Employee::where('employee_rfid', $rfid)->first();
                // Query to get the employee based on the RFID for compact
                $employees = Employee::where('employee_rfid', $rfid)->get();
                    
                if ($employee) {
                    // Get the current datetime in Kuala Lumpur timezone
                    // $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
                    // $now = new DateTime('now', new DateTimeZone('Asia/Taipei'));

                    // if (stripos($employee->department->department_abbreviation, 'VDT') === false) {
                    //     return redirect()->route('attendance.portal.vdt')->with('error', 'You\'re not from VDT. Attendance Invalid.');
                    // }
                    // for restriction

                    $now = Carbon::now('Asia/Taipei');
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
                        $checkInTime = new DateTime($firstTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
                        $interval = $now->diff($checkInTime);
                        $minutes = $interval->i + ($interval->h * 60);
                        if ($minutes >= 45) {
                            $intervalAllowed = true;
                        }
                    }

                    // Check if the employee has already checked out in the afternoon
                    if ($timeInCount == 2 && $timeOutCount == 2) {

                        return redirect()->route('attendance.portal.vdt')->with('success', 'Attendance completed. Safe travels home!');
                        // return response()->json([
                        //     'message' => 'Already checked out in the afternoon. Go home safely!',
                        // ], 403);
                    }
                    else if ($timeInCount == 1 && $timeOutCount == 1) {
                            
                        $intervalAllowed = false;

                        if ($firstTimeOut) {
                            $checkOutTime = new DateTime($firstTimeOut->check_out_time, new DateTimeZone('Asia/Taipei'));
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
                            $attendanceIn->save();

                            $first_time_in = $attendanceIn->check_in_time = $formattedDateTime;
                            // return response()->json([
                            //     'message' => 'PM Time-in recorded successfully.',
                            //     'employee' => $employee,
                            //     'check_in_time' => $formattedDateTime,
                            // ], 200);
                            return view('attendance-profile_time_in_employee_vdt', compact('employees', 'first_time_in'));
                        } else {
                            // return response()->json([
                            //     'message' => 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out.',
                            // ], 403);
                            // return redirect()->route('admin.attendance.time-in.portal')->with('error', 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out!');
                            return redirect()->route('attendance.portal.vdt')->with('error', 'Please wait 45 minutes to time in!');
                        }

                    } elseif ($timeInCount == 1 && $timeOutCount == 0) {
                        
                        if ($intervalAllowed) 
                        {
                            // First check-out (AM)
                            $attendanceOut = new EmployeeAttendanceTimeOut();
                            $attendanceOut->employee_id = $employee->id;
                            $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
                            $attendanceOut->status = "Outside Campus";
                            $attendanceOut->save();

                            $first_time_out = $attendanceOut->check_out_time = $formattedDateTime;
                            // return response()->json([
                            //     'message' => 'AM Time-out recorded successfully.',
                            //     'employee' => $employee,
                            //     'check_out_time' => $formattedDateTime,
                            // ], 200);
                            return view('attendance-profile_time_out_employee_vdt', compact('employees','first_time_out'));
                        } else {
                            // return response()->json([
                            //     'message' => 'Already Time In Morning.',
                            // ], 403);
                            return redirect()->route('attendance.portal.vdt')->with('success', 'Already Timed In!');
                        }

                    } elseif ($timeInCount == 2 && $timeOutCount == 1) {
                            // Check interval for second check-out
                        $intervalAllowed = false;
                        if ($lastTimeIn) {
                            $checkInTime = new DateTime($lastTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
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
                            $attendanceOut->save();

                            $first_time_out = $attendanceOut->check_out_time = $formattedDateTime;
                            // return response()->json([
                            //     'message' => 'PM Time-out recorded successfully.',
                            //     'employee' => $employee,
                            //     'check_out_time' => $formattedDateTime,
                            // ], 200);
                            return view('attendance-profile_time_out_employee_vdt', compact('employees','first_time_out'));
                        } else {
                            // return response()->json([
                            //     'message' => 'Already Time-in Afternoon.',
                            // ], 403);
                            return redirect()->route('attendance.portal.vdt')->with('success', 'Already Timed In!');
                        }

                    } else {
                        // First time-in (AM)
                        $attendanceIn = new EmployeeAttendanceTimeIn();
                        $attendanceIn->employee_id = $employee->id;
                        $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
                        $attendanceIn->status = "On-campus";
                        $attendanceIn->save();

                        $first_time_in = $attendanceIn->check_in_time = $formattedDateTime;

                        return view('attendance-profile_time_in_employee_vdt', compact('employees', 'first_time_in'));
                        // return response()->json([
                        //     'message' => 'AM Time-in recorded successfully.',
                        //     'employee' => $employee,
                        //     'check_in_time' => $formattedDateTime,
                        // ], 200);
                    }
                } else {
                    // Handle case where employee with given RFID is not found
                    // return response()->json(['error' => 'Employee not found.'], 404);
                    return redirect()->route('attendance.portal.vdt')->with('error', 'RFID not Recognized!');
                }
            } else {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }
       
    }

    public function fetchLatest()
    {
        // Retrieve data for 'check_in' and 'check_out'
        $curdateDataIn = StudentAttendanceTimeIn::with('student.course')
            ->whereDate('check_in_time', now())
            ->latest()
            ->take(1)
            ->get();

        $curdateDataOut = StudentAttendanceTimeOut::with('student.course')
            ->whereDate('check_out_time', now())
            ->latest()
            ->take(1)
            ->get();

        // Optimize by processing the students' profile image logic only once
        $this->addProfileImages($curdateDataIn);
        $this->addProfileImages($curdateDataOut);

        // Return the result
        return response()->json([
            'curdateDataIn' => $curdateDataIn,
            'curdateDataOut' => $curdateDataOut
        ]);
    }

    // Reusable method to add profile image URLs
    private function addProfileImages($data)
    {
        $data->each(function ($record) {
            if ($record->student) {
                $record->student->profile_image = $record->student->student_photo
                    ? asset('storage/student_photo/' . $record->student->student_photo)
                    : asset('assets/img/user.png');
            }
        });
    }


    // public function fetchLatest()
    // {
    //     $curdateDataIn = StudentAttendanceTimeIn::with('student.course')
    //         ->whereDate('check_in_time', now())
    //         ->latest()
    //         ->take(1)
    //         ->get();
            

    //     $curdateDataOut = StudentAttendanceTimeOut::with('student.course')
    //         ->whereDate('check_out_time', now())
    //         ->latest()
    //         ->take(1)
    //         ->get();

    //     // Safely add full image URL if the student exists
    //     $curdateDataIn->each(function ($record) {
    //         if ($record->student) {
    //             $record->student->profile_image = $record->student->student_photo
    //                 ? asset('storage/student_photo/' . $record->student->student_photo)
    //                 : asset('assets/img/user.png');
    //         }
    //     });

    //     $curdateDataOut->each(function ($record) {
    //         if ($record->student) {
    //             $record->student->profile_image = $record->student->student_photo
    //                 ? asset('storage/student_photo/' . $record->student->student_photo)
    //                 : asset('assets/img/user.png');
    //         }
    //     });

    //     return response()->json([
    //         'curdateDataIn' => $curdateDataIn,
    //         'curdateDataOut' => $curdateDataOut
    //     ]);
    // }




    // STUDENT PORTAL IN / OUT
   public function portalTimeInStudent()
{
    // Retrieve any user with the role 'admin'
    $adminUser = User::where('role', 'admin')->first();

    // Check if there is an admin user
    if ($adminUser) {
        $current_date = now()->setTimezone('Asia/Taipei')->format('Y-m-d');
        $current_time = now()->setTimezone('Asia/Taipei')->format('H:i:s');

        $timezone = 'Asia/Taipei';

        $current_date1 = Carbon::now($timezone)->format('D, M d, Y'); // E.g., "Mon, Jul 30, 2024"
        $current_time1 = Carbon::now($timezone)->format('h:i:s A'); 

        // Retrieve attendance data for the current date
        $curdateDataIn = StudentAttendanceTimeIn::whereDate('check_in_time', $current_date)->get();
        $curdateDataOut = StudentAttendanceTimeOut::whereDate('check_out_time', $current_date)->get();
        

        if (request()->ajax()) {
            // Log the attendance data being returned
            \Log::info('Attendance data:', [
                'curdateDataIn' => $curdateDataIn,
                'curdateDataOut' => $curdateDataOut,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance data fetched successfully',
                'curdateDataIn' => $curdateDataIn,
                'curdateDataOut' => $curdateDataOut,
            ]);
                // Return view with the attendance data for page load
               
        }
         return view('attendance_time_in_student', compact('curdateDataIn', 'curdateDataOut', 'current_time1', 'current_date1'));
    }
}


        




    public function submitAttendanceStudent(Request $request)
    {
        $request->validate([
        'user_rfid' => 'required|string|max:255',
    ]);

    $adminUser = User::where('role', 'admin')->first();

    if ($adminUser) {
        $rfid = $request->input('user_rfid');

        // Query to get the student based on the RFID
        $student = Student::where('student_rfid', $rfid)->first();

        if ($student) {
            // Get the current datetime in Taipei timezone
            $now = Carbon::now('Asia/Taipei');
            $formattedDateTime = $now->format('Y-m-d H:i:s');

            // Get the count of time-in and time-out records for today
            $timeInCount = StudentAttendanceTimeIn::where('student_id', $student->id)
                ->whereDate('check_in_time', $now->format('Y-m-d'))
                ->count();

            $timeOutCount = StudentAttendanceTimeOut::where('student_id', $student->id)
                ->whereDate('check_out_time', $now->format('Y-m-d'))
                ->count();

            // Logic for alternating time-in and time-out
            if ($timeInCount == $timeOutCount) {
                // Time-in logic
                $attendanceIn = new StudentAttendanceTimeIn();
                $attendanceIn->student_id = $student->id;
                $attendanceIn->check_in_time = $formattedDateTime;
                $attendanceIn->status = "On-campus";
                $attendanceIn->save();

                return response()->json(['success' => true, 'message' => 'Time-in recorded successfully.']);
            } else {
                // Time-out logic
                $attendanceOut = new StudentAttendanceTimeOut();
                $attendanceOut->student_id = $student->id;
                $attendanceOut->check_out_time = $formattedDateTime;
                $attendanceOut->status = "Outside Campus";
                $attendanceOut->save();

                return response()->json(['success' => true, 'message' => 'Time-out recorded successfully.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'RFID not recognized.']);
        }
    } else {
        return response()->json(['success' => false, 'message' => 'Unauthorized access.']);
    }
    }










    // public function submitAttendanceStudent(Request $request)
    // {
    //     $request->validate([
    //         'user_rfid' => 'required|string|max:255',
    //     ]);

    //         $adminUser = User::where('role', 'admin')->first();
            
    //         if($adminUser)
    //         {
    //             $rfid = $request->input('user_rfid');
                
    //             // Query to get the employee based on the RFID
    //             $student= Student::where('employee_rfid', $rfid)->first();
    //             // Query to get the employee based on the RFID for compact
    //             $students = Student::where('employee_rfid', $rfid)->get();
                    
    //             if ($student) {
    //                 // Get the current datetime in Kuala Lumpur timezone
    //                 // $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
    //                 // $now = new DateTime('now', new DateTimeZone('Asia/Taipei'));
    //                 $now = Carbon::now('Asia/Taipei');
    //                 // Format datetime for database insertion
    //                 $formattedDateTime = $now->format('Y-m-d H:i:s');

    //                 // Get the count of time-in records for today
    //                 $timeInCount = StudentAttendanceTimeIn::where('student_id', $student->id)
    //                     ->whereDate('check_in_time', $now->format('Y-m-d'))
    //                     ->count();

    //                 // Get the first time-in record for today
    //                 $firstTimeIn = StudentAttendanceTimeIn::where('student_id', $student->id)
    //                     ->whereDate('check_in_time', $now->format('Y-m-d'))
    //                     ->first();

    //                 // Get the count of time-out records for today
    //                 $timeOutCount = StudentAttendanceTimeOut::where('student_id', $student->id)
    //                     ->whereDate('check_out_time', $now->format('Y-m-d'))
    //                     ->count();

    //                 // Get the first time-in record for today
    //                 $firstTimeOut = StudentAttendanceTimeOut::where('student_id', $student->id)
    //                     ->whereDate('check_out_time', $now->format('Y-m-d'))
    //                     ->first();

    //                 // Get the last time-in record for today
    //                 $lastTimeIn = StudentAttendanceTimeIn::where('student_id', $student->id)
    //                     ->whereDate('check_in_time', $now->format('Y-m-d'))
    //                     ->latest('check_in_time')
    //                     ->first();

                        

    //                 $intervalAllowed = false;
    //                 // Check interval for first check-out
    //                 if ($firstTimeIn) {
    //                     $checkInTime = new DateTime($firstTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
    //                     $interval = $now->diff($checkInTime);
    //                     $minutes = $interval->i + ($interval->h * 60);
    //                     if ($minutes >= 45) {
    //                         $intervalAllowed = true;
    //                     }
    //                 }

    //                 // Check if the employee has already checked out in the afternoon
    //                 if ($timeInCount == 2 && $timeOutCount == 2) {

    //                     return redirect()->route('attendance.portal')->with('success', 'Attendance completed. Safe travels home!');
    //                     // return response()->json([
    //                     //     'message' => 'Already checked out in the afternoon. Go home safely!',
    //                     // ], 403);
    //                 }
    //                 else if ($timeInCount == 1 && $timeOutCount == 1) {
                            
    //                     $intervalAllowed = false;

    //                     if ($firstTimeOut) {
    //                         $checkOutTime = new DateTime($firstTimeOut->check_out_time, new DateTimeZone('Asia/Taipei'));
    //                         $interval = $now->diff($checkOutTime);
    //                         $minutes = $interval->i + ($interval->h * 60);
    //                         if ($minutes >= 45) {
    //                             $intervalAllowed = true;
    //                         }
    //                     }

    //                     if ($intervalAllowed) 
    //                     {
    //                         // Second time-in (PM), no second check-out
    //                         $attendanceIn = new EmployeeAttendanceTimeIn();
    //                         $attendanceIn->employee_id = $employee->id;
    //                         $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
    //                         $attendanceIn->status = "On-campus";
    //                         $attendanceIn->save();

    //                         // return response()->json([
    //                         //     'message' => 'PM Time-in recorded successfully.',
    //                         //     'employee' => $employee,
    //                         //     'check_in_time' => $formattedDateTime,
    //                         // ], 200);
    //                         return view('attendance-profile_time_in_employee', compact('employees'));
    //                     } else {
    //                         // return response()->json([
    //                         //     'message' => 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out.',
    //                         // ], 403);
    //                         // return redirect()->route('admin.attendance.time-in.portal')->with('error', 'Already Check-out in morning! Afternoon - Check-In not allowed yet. Please wait 45 minutes after check-out!');
    //                         return redirect()->route('attendance.portal')->with('error', 'Please wait 45 minutes for Afternoon time in!');
    //                     }

    //                 } elseif ($timeInCount == 1 && $timeOutCount == 0) {
                        
    //                     if ($intervalAllowed) 
    //                     {
    //                         // First check-out (AM)
    //                         $attendanceOut = new EmployeeAttendanceTimeOut();
    //                         $attendanceOut->employee_id = $employee->id;
    //                         $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
    //                         $attendanceOut->status = "Outside Campus";
    //                         $attendanceOut->save();

    //                         // return response()->json([
    //                         //     'message' => 'AM Time-out recorded successfully.',
    //                         //     'employee' => $employee,
    //                         //     'check_out_time' => $formattedDateTime,
    //                         // ], 200);
    //                         return view('attendance-profile_time_out_employee', compact('employees'));
    //                     } else {
    //                         // return response()->json([
    //                         //     'message' => 'Already Time In Morning.',
    //                         // ], 403);
    //                         return redirect()->route('attendance.portal')->with('success', 'Already Timed In!');
    //                     }

    //                 } elseif ($timeInCount == 2 && $timeOutCount == 1) {
    //                         // Check interval for second check-out
    //                     $intervalAllowed = false;
    //                     if ($lastTimeIn) {
    //                         $checkInTime = new DateTime($lastTimeIn->check_in_time, new DateTimeZone('Asia/Taipei'));
    //                         $interval = $now->diff($checkInTime);
    //                         $minutes = $interval->i + ($interval->h * 60);
    //                         if ($minutes >= 45) {
    //                             $intervalAllowed = true;
    //                         }
    //                     }

    //                     if ($intervalAllowed) {
    //                         // Second check-out (PM)
    //                         $attendanceOut = new EmployeeAttendanceTimeOut();
    //                         $attendanceOut->employee_id = $employee->id;
    //                         $attendanceOut->check_out_time = $formattedDateTime; // Store formatted datetime
    //                         $attendanceOut->status = "Outside Campus";
    //                         $attendanceOut->save();

    //                         // return response()->json([
    //                         //     'message' => 'PM Time-out recorded successfully.',
    //                         //     'employee' => $employee,
    //                         //     'check_out_time' => $formattedDateTime,
    //                         // ], 200);
    //                         return view('attendance-profile_time_out_employee', compact('employees'));
    //                     } else {
    //                         // return response()->json([
    //                         //     'message' => 'Already Time-in Afternoon.',
    //                         // ], 403);
    //                         return redirect()->route('attendance.portal')->with('success', 'Already Timed In!');
    //                     }

    //                 } else {
    //                     // First time-in (AM)
    //                     $attendanceIn = new EmployeeAttendanceTimeIn();
    //                     $attendanceIn->employee_id = $employee->id;
    //                     $attendanceIn->check_in_time = $formattedDateTime; // Store formatted datetime
    //                     $attendanceIn->status = "On-campus";
    //                     $attendanceIn->save();

    //                     return view('attendance-profile_time_in_employee', compact('employees'));
    //                     // return response()->json([
    //                     //     'message' => 'AM Time-in recorded successfully.',
    //                     //     'employee' => $employee,
    //                     //     'check_in_time' => $formattedDateTime,
    //                     // ], 200);
    //                 }
    //             } else {
    //                 // Handle case where employee with given RFID is not found
    //                 // return response()->json(['error' => 'Employee not found.'], 404);
    //                 return redirect()->route('attendance.portal')->with('error', 'RFID not Recognized!');
    //             }
    //         } else {
    //             return redirect()->back()->with('error', 'Unauthorized access.');
    //         }
       
    // }


     // public function portalTimeIn()
    // {
    //     // Retrieve any user with the role 'admin'
    //     $adminUser = User::where('role', 'admin')->first();
    //     // Check if there is an admin user

    //     if ($adminUser) {
    //         // $current_date = now()->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d');
    //         $current_date = now()->setTimezone('Asia/Taipei')->format('Y-m-d');
    //         $current_time = now()->setTimezone('Asia/Taipei')->format('H:i:s');

    //            $timezone = 'Asia/Taipei';

    //         $current_date1 = Carbon::now($timezone)->format('D, M d, Y'); // E.g., "Mon, Jul 30, 2024"
    //         $current_time1 = Carbon::now($timezone)->format('h:i:s A'); 

    //         // Retrieve attendance data for the current date
    //         // $curdateDataIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $current_date)->get();
    //         // $curdateDataOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $current_date)->get();

    //         // $curdateDataIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $current_date)
    //         //     ->whereHas('employee.department', function($query) {
    //         //         $query->where('department_abbreviation', 'NOT LIKE', '%VDT%');
    //         //     })
    //         //     ->orderBy('check_in_time', 'asc') // Order by check_in_time in descending order
    //         //     ->get();

    //         // $curdateDataIn = EmployeeAttendanceTimeIn::with('employee.department')
    //         //     ->whereDate('check_in_time', $current_date) // Filter by the current date
    //         //     ->whereHas('employee.department', function($query) {
    //         //         // Exclude departments with 'VDT' in the abbreviation
    //         //         $query->where('department_abbreviation', 'NOT LIKE', '%VDT%');
    //         //     })
    //         //     // ->whereNotIn('status', ['On Leave', 'Official Travel', 'Holiday', 'Weekend']) // Exclude records with certain statuses
    //         //     ->whereIn('status', ['On-campus'])
    //         //     ->orderBy('check_in_time', 'asc') // Order by check_in_time ascending
    //         //     ->get();

    //         // // Retrieve data for 'check_out' with filtering
    //         // $curdateDataOut = EmployeeAttendanceTimeOut::with('employee.department')
    //         //     ->whereDate('check_out_time', $current_date) // Filter by the current date
    //         //     ->whereHas('employee.department', function($query) {
    //         //         // Exclude departments with 'VDT' in the abbreviation
    //         //         $query->where('department_abbreviation', 'NOT LIKE', '%VDT%');
    //         //     })
    //         //     // ->whereNotIn('status', ['On Leave', 'Official Travel', 'Holiday', 'Weekend']) // Exclude records with certain statuses
    //         //     ->whereIn('status', ['Outside Campus'])
    //         //     ->orderBy('check_out_time', 'asc') // Order by check_out_time ascending
    //         //     ->get();


    //         // Return view with the attendance data
    //         return view('attendance_time_in', compact('current_time1', 'current_date1'));
    //     }

    //     // Redirect with an error message if no admin user is found
    //     return redirect()->back()->with('error', 'No admin user found.');
    // }
}
