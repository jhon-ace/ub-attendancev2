<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Student;
use App\Models\Admin\School;
use App\Models\Admin\Department;
use App\Models\Admin\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Admin\StudentAttendanceTimeIn;
use App\Models\Admin\StudentAttendanceTimeOut;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShowStudentAttendance extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'student_id';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment5 = null;
    public $selectedCourse5 = null;
    public $departmentsToShow;
    public $schoolToShow;
    public $departmentToShow;
    public $studentsToShow;
    public $selectedCourseToShow;
    public $selectedStudent5 = null;
    public $selectedAttendanceToShow;
    public $selectedStudentToShow;
    public $startDate = null;
    public $endDate = null;
    public $selectedMonth;
    public $searchTerm = '';
    public $selectedYear;
    public $years;


    protected $listeners = ['updateMonth','updateEmployees', 'updateEmployeesByDepartment', 'updateStudentsByCourse', 'updateAttendanceByStudent', 'updateAttendanceByDateRange'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->updateAttendanceByDateRange();
        $this->startDate = null;
        $this->endDate = null;
    }

    public function mount()
    {
        if (Auth::check() && Auth::user()->school) {
            $this->selectedSchool = Auth::user()->school->id;
        }


        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->selectedDepartment5 = session('selectedDepartment5', null);
        $this->selectedCourse5 = session('selectedCourse5', null);
        $this->selectedStudent5 = session('selectedStudent5', null);
        $this->departmentsToShow = collect([]);
        $this->schoolToShow = collect([]);
        $this->departmentToShow = collect([]);
        $this->studentsToShow = collect([]);
        $this->selectedCourseToShow = collect([]);
        $this->selectedAttendanceToShow = collect([]);
        $this->selectedStudentToShow = collect([]);
    }

    public function updatingSelectedSchool()
    {
        $this->resetPage();
        $this->updateEmployees();
    }

    public function updatingSelectedDepartment()
    {
        $this->resetPage();
        $this->selectedCourse = null;
        $this->departmentToShow = null;
        $this->updateEmployeesByDepartment();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function updateMonth()
    {   
        
        if ($this->selectedMonth && $this->startDate && $this->endDate) {
            // Get time-in records for the selected employee and month
            $this->attendancesToShow = StudentAttendanceTimeIn::where('student_id', $this->selectedStudent5)
                ->whereYear('check_in_time', $this->selectedYear)
                ->whereMonth('check_in_time', $this->selectedMonth) // Filter by selected month
                ->get();

            // Fetch corresponding time-out records
            $this->attendancesToShow = StudentAttendanceTimeOut::where('student_id', $this->selectedStudent5)
                ->whereYear('check_out_time', $this->selectedYear)
                ->whereMonth('check_out_time', $this->selectedMonth) // Filter by selected month
                ->get();

        } else if($this->selectedMonth){
            $this->attendancesToShow = StudentAttendanceTimeIn::where('student_id', $this->selectedStudent5)
                ->whereYear('check_in_time', $this->selectedYear)
                ->whereMonth('check_in_time', $this->selectedMonth) // Filter by selected month
                ->get();

            // Fetch corresponding time-out records
            $this->attendancesToShow = StudentAttendanceTimeOut::where('student_id', $this->selectedStudent5)
                ->whereYear('check_out_time', $this->selectedYear)
                ->whereMonth('check_out_time', $this->selectedMonth) // Filter by selected month
                ->get();
        } else {
            // Reset data if no month is selected
            $this->attendancesToShow = collect();
            $this->timeOutAttendances = collect();
            $this->startDate = null; // Reset start date
            $this->endDate = null;   // Reset end date
        }
    }

   public function render()
    {
        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => date('F', mktime(0, 0, 0, $month, 1))];
        });

        $query = Student::query()->with(['course.school', 'course.department']);
        $queryTimeIn = StudentAttendanceTimeIn::query()
            ->with(['student.course']);

        $queryTimeOut = StudentAttendanceTimeOut::query()
            ->with(['student.course']);
        // Apply search filters
        // $query = $this->applySearchFilters($query);

        // Apply selected school filter
        if ($this->selectedSchool) {
            $query->whereHas('course', function (Builder $query) {
                $query->where('school_id', $this->selectedSchool);
            });
            $this->schoolToShow = School::find($this->selectedSchool);
        } else {
            $this->schoolToShow = null;
        }

        // Apply selected department filter
        if ($this->selectedDepartment5) {
            $query->whereHas('course', function (Builder $query) {
                $query->where('department_id', $this->selectedDepartment5);
            });
            $this->departmentToShow = Department::find($this->selectedDepartment5);

            // Fetch courses for the selected department
            $courses = Course::where('department_id', $this->selectedDepartment5)->get();
        } else {
            $this->departmentToShow = null;
            $courses = Course::all(); // Fetch all courses if no department selected
        }

        //Apply selected course filter
        // if ($this->selectedCourse5) {
        //     $query->where('course_id', $this->selectedCourse5);
        //     $this->selectedCourseToShow = Course::find($this->selectedCourse5);

        //     $students = Student::where('course_id', $this->selectedCourse5)->get();
        // } else {
        //     $this->selectedCourseToShow = null;
        //     $students = Student::all();
        // }

        if ($this->selectedCourse5) {
            $query->where('course_id', $this->selectedCourse5);
            $this->selectedCourseToShow = Course::find($this->selectedCourse5);

            // $students = Student::where('course_id', $this->selectedCourse5)->get();
            // $students = Student::where('course_id', $this->selectedCourse5)->paginate(10);
            // Initialize the query builder for the Student model
            $studentsQuery = Student::query();

            // Apply course filter if a course is selected
            if (!empty($this->selectedCourse5)) {
                $studentsQuery->where('course_id', $this->selectedCourse5);
            }

            $studentsQuery->where(function ($query) {
                $searchTerm = '%' . strtolower($this->searchTerm) . '%'; // Convert the search term to lowercase

                // Ensure the search term is not empty
                if (!empty($this->searchTerm)) {
                    $query->whereRaw('LOWER(student_firstname) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(student_lastname) LIKE ?', [$searchTerm])
                        ->orWhereRaw('LOWER(student_id) LIKE ?', [$searchTerm]);
                }
            });



            // Apply sorting if necessary
            if ($this->sortField && $this->sortDirection) {
                $studentsQuery->orderBy($this->sortField, $this->sortDirection);
            }

            // Execute the query to get the matching students
            $students = $studentsQuery->paginate(10);
            

        } else {
            $this->selectedCourseToShow = null;
            // $students = Student::all();
            $students = Student::paginate(10);

        }

        if ($this->selectedStudent5) {
            $query->where('id', $this->selectedStudent5);
            $this->selectedStudentToShow = Student::find($this->selectedStudent5);
        } else {
            $this->selectedStudentToShow = null;
        }



        if ($this->selectedStudent5) {
            $queryTimeIn->where('student_id', $this->selectedStudent5);
            $this->selectedAttendanceToShow = StudentAttendanceTimeIn::find($this->selectedStudent5);

            $queryTimeOut->where('student_id', $this->selectedStudent5);
            $this->selectedAttendanceToShow = StudentAttendanceTimeOut::find($this->selectedStudent5);
        } else {
            $this->selectedAttendanceToShow = null;
        }

        if ($this->startDate && $this->endDate) {
            $queryTimeIn->whereDate('check_in_time', '>=', $this->startDate)
                        ->whereDate('check_in_time', '<=', $this->endDate);

            $queryTimeOut->whereDate('check_out_time', '>=', $this->startDate)
                        ->whereDate('check_out_time', '<=', $this->endDate);
                        
            $selectedAttendanceByDate = $queryTimeIn->get();// Fetch data and assign to selectedAttendanceByDate
            
            $this->selectedAttendanceByDate = $selectedAttendanceByDate;   
        }
        else {
        //     $attendanceTimeIn = $queryTimeIn->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(50);

        // $attendanceTimeOut = $queryTimeOut->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(50);
        }
        
        // $attendanceTimeIn = $queryTimeIn->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(50);

        // $attendanceTimeOut = $queryTimeOut->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate(50);
         $queryTimeIn->whereDay('check_in_time', '>=', 1)
                        ->whereDay('check_in_time', '<=', 31);

            $queryTimeOut->whereDay('check_out_time', '>=', 1)
                        ->whereDay('check_out_time', '<=', 31);

        $currentYear = now()->year; 


         $attendanceTimeIn = $queryTimeIn
                ->whereMonth('check_in_time', $this->selectedMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year
                ->orderBy('student_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

        $attendanceTimeOut = $queryTimeOut
                ->whereMonth('check_out_time', $this->selectedMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('student_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();


        $attendanceData = [];
        $overallTotalHours = 0;

        foreach ($attendanceTimeIn as $attendance) {
            // Initialize AM and PM hours worked
            $hoursWorkedAM = 0;
            $hoursWorkedPM = 0;

            // Find corresponding check_out_time
            $checkOut = $attendanceTimeOut->where('student_id', $attendance->student_id)
                                        ->where('check_out_time', '>=', $attendance->check_in_time)
                                        ->first();

            // Calculate hours worked
            if ($checkOut) {
                // Extract dates from check_in_time and check_out_time
                $checkInDate = date('Y-m-d', strtotime($attendance->check_in_time));
                $checkOutDate = date('Y-m-d', strtotime($checkOut->check_out_time));

                // Check if dates match
                if ($checkInDate === $checkOutDate) {
                    // Calculate hours worked
                    $checkIn = strtotime($attendance->check_in_time);
                    $checkOutTime = strtotime($checkOut->check_out_time);

                    // Split hours into AM and PM
                    if ($checkIn < strtotime($checkInDate . ' 12:00 PM')) {
                        if ($checkOutTime <= strtotime($checkInDate . ' 1:00 PM')) {
                            // Both check-in and check-out are in the AM
                            $hoursWorkedAM = ($checkOutTime - $checkIn) / 3600;
                        } else {
                            // Check-in is in AM and check-out is in PM
                            $hoursWorkedAM = (strtotime($checkInDate . ' 12:00 PM') - $checkIn) / 3600;
                            $hoursWorkedPM = ($checkOutTime - strtotime($checkInDate . ' 01:00 PM')) / 3600;
                        }
                    } else {
                        // Both check-in and check-out are in the PM
                        $hoursWorkedPM = ($checkOutTime - $checkIn) / 3600;
                    }

                    // Calculate total hours worked
                    $totalHoursWorked = $hoursWorkedAM + $hoursWorkedPM;

                    // Prepare the key for $attendanceData
                    $key = $attendance->student_id . '-' . $checkInDate;

                    // Check if this entry already exists in $attendanceData
                    if (isset($attendanceData[$key])) {
                        // Update existing entry
                        $attendanceData[$key]->hours_workedAM += $hoursWorkedAM;
                        $attendanceData[$key]->hours_workedPM += $hoursWorkedPM;
                        $attendanceData[$key]->total_hours_worked += $totalHoursWorked;
                    } else {
                        // Create new entry
                        $attendanceData[$key] = (object) [
                            'student_id' => $attendance->student_id,
                            'worked_date' => $checkInDate,
                            'hours_workedAM' => $hoursWorkedAM,
                            'hours_workedPM' => $hoursWorkedPM,
                            'total_hours_worked' => $totalHoursWorked,
                            'remarks' => 'Present', // Assuming it's always present when hours are recorded
                        ];
                    }

                    // Add total hours worked to overall total
                    $overallTotalHours += $totalHoursWorked;
                } else {
                    // Dates do not match, mark as absent
                    $attendanceData[] = (object) [
                        'student_id' => $attendance->student_id,
                        'worked_date' => $checkInDate,
                        'hours_workedAM' => 0,
                        'hours_workedPM' => 0,
                        'total_hours_worked' => 0,
                        'remarks' => 'Absent',
                    ];
                }
            } else {
                // No check_out_time found, mark as absent
                $checkInDate = date('Y-m-d', strtotime($attendance->check_in_time));
                $attendanceData[] = (object) [
                    'student_id' => $attendance->student_id,
                    'worked_date' => $checkInDate,
                    'hours_workedAM' => 0,
                    'hours_workedPM' => 0,
                    'total_hours_worked' => 0,
                    'remarks' => 'Absent',
                ];
            }
        }

        $schools = School::all();
        $departments = Department::where('school_id', $this->selectedSchool)
            ->where('dept_identifier', 'student')
            ->get();

        $studentsCounts = Student::select('course_id', \DB::raw('count(*) as student_count'))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        return view('livewire.admin.show-student-attendance', [
            'overallTotalHours' => $overallTotalHours,
            'attendanceData' =>$attendanceData,
            'attendanceTimeIn' => $attendanceTimeIn,
            'attendanceTimeOut' => $attendanceTimeOut,
            'students' => $students,
            'schools' => $schools,
            'departments' => $departments,
            'studentsCounts' => $studentsCounts,
            'schoolToShow' => $this->schoolToShow,
            'departmentToShow' => $this->departmentToShow,
            'selectedCourseToShow' => $this->selectedCourseToShow,
            'selectedStudentToShow' => $this->selectedStudentToShow,
            'selectedAttendanceToShow' => $this->selectedAttendanceToShow,
            'courses' => $courses, // Pass the courses to the view
            'months' => $months,
        ]);
    }




    public function updateEmployees()
    {
        if ($this->selectedSchool) {
            $this->departmentsToShow = Department::where('school_id', $this->selectedSchool)->get();
        } else {
            $this->departmentsToShow = collect();
        }

        $this->selectedDepartment = null;
        $this->departmentToShow = null;
    }

    public function updateEmployeesByDepartment()
    {
        if ($this->selectedDepartment5 && $this->selectedSchool) {
            $this->departmentToShow = Department::where('id', $this->selectedDepartment5)
                ->where('school_id', $this->selectedSchool)
                ->first();
        } else {
            $this->departmentToShow = null;
        }
    }

    public function updateStudentsByCourse()
    {
        if ($this->selectedCourse5) {
            $this->studentsToShow = Student::where('course_id', $this->selectedCourse5)->get();
        } else {
            $this->studentsToShow = collect();
        }
    }

    public function updateAttendanceByStudent()
    {
        if ($this->selectedStudent5) {
            $this->selectedAttendanceToShow = StudentAttendanceTimeIn::where('student_id', $this->selectedStudent5)->get();
            $this->selectedAttendanceToShow = StudentAttendanceTimeOut::where('student_id', $this->selectedStudent5)->get();
        } else {
            $this->selectedAttendanceToShow = collect();
            // $this->startDate = null; // Reset start date
            // $this->endDate = null; // Reset end date
        }
    }


    public function updateAttendanceByDateRange()
    {
        // Base query for StudentAttendanceTimeIn with related student data
        $queryTimeIn = StudentAttendanceTimeIn::query()
            ->with(['student'])
            ->where('student_id', $this->selectedStudent5);

        // Apply date range filter only if both startDate and endDate are selected
        if ($this->startDate && $this->endDate) {
            // Ensure startDate and endDate are valid dates and endDate is not before startDate
            if ($this->startDate <= $this->endDate) {
                $queryTimeIn->whereDate('check_in_time', '>=', $this->startDate)
                            ->whereDate('check_in_time', '<=', $this->endDate);
            } else {
                // Handle the case where endDate is before startDate
                 $this->selectedAttendanceToShow = StudentAttendanceTimeIn::where('student_id', $this->selectedStudent5)->get();
            $this->selectedAttendanceToShow = StudentAttendanceTimeOut::where('student_id', $this->selectedStudent5)->get();
            }
        }

        // Execute the query and get the results
          $this->selectedAttendanceToShow = StudentAttendanceTimeIn::where('student_id', $this->selectedStudent5)->get();
            $this->selectedAttendanceToShow = StudentAttendanceTimeOut::where('student_id', $this->selectedStudent5)->get();
    }

    public function generatePDF()
    {
        $savePath = storage_path('/app/generatedPDF'); // Default save path (storage/app/)
        // $savePath = 'C:/Users/YourUsername/Downloads/'; // Windows example
        //  $currentYear = Carbon::now()->year;
        //     $currentMonth = Carbon::now()->month;
        $currentMonth = $this->selectedMonth;  // Get the current month
        $currentYear = $this->selectedYear;  

        try {

           // Determine the filename dynamically with date included if both startDate and endDate are selected
            if ($this->startDate && $this->endDate) {
               
                 $fullStartDate = $this->startDate;
                $fullEndDate = $this->endDate;

                // Format using Carbon
                $selectedStartDate = Carbon::parse($fullStartDate)->format('jS F Y');
                $selectedEndDate = Carbon::parse($fullEndDate)->format('jS F Y');


                $dateRange = $selectedStartDate . ' to ' . $selectedEndDate;
            } else {
                $dateRange = Carbon::createFromFormat('m', $this->selectedMonth)->format('F') . ', ' . $this->selectedYear;
            }
            
           $student = Student::find($this->selectedStudent5); 


            // Construct the filename with the date range if available
            $filename = $student->student_lastname . ', ' . $student->student_firstname . ' ' . $student->student_middlename . ' - ' . $dateRange . '.pdf';


            // Base query for EmployeeAttendanceTimeIn with left join to EmployeeAttendanceTimeOut
            $queryTimeIn = StudentAttendanceTimeIn::query()
                ->with(['student']);
            $queryTimeOut = StudentAttendanceTimeOut::query()
                ->with(['student']);
            
            // Apply selected student filter
            if ($this->selectedStudent5) {
                $queryTimeIn->where('student_id', $this->selectedStudent5);
                $this->selectedEmployeeToShow = Student::find($this->selectedStudent5);
                $queryTimeOut->where('student_id', $this->selectedStudent5);
                $this->selectedEmployeeToShow = Student::find($this->selectedStudent5);
            } else {
                $this->selectedEmployeeToShow = null;
            }

            $currentMonth = $this->selectedMonth;  // Get the current month
            $currentYear = $this->selectedYear;  

            // Apply date range filter if both dates are set
            if ($this->startDate && $this->endDate) {
                $queryTimeIn->whereDay('check_in_time', '>=', $this->startDate)
                            ->whereMonth('check_in_time', $currentMonth)  // Match current month
                            ->whereYear('check_in_time', $currentYear) 
                            ->whereDay('check_in_time', '<=', $this->endDate);


                $queryTimeOut->whereDay('check_out_time', '>=', $this->startDate)
                            ->whereMonth('check_out_time', $currentMonth)  // Match current month
                            ->whereYear('check_out_time', $currentYear) 
                            ->whereDay('check_out_time', '<=', $this->endDate);
                
                           $attendanceTimeIn = $queryTimeIn
                ->whereMonth('check_in_time', $currentMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year        
                ->orderBy('student_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

            $attendanceTimeOut = $queryTimeOut
                ->whereMonth('check_out_time', $currentMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('student_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();
            } else {
                $attendanceTimeIn = $queryTimeIn
                ->whereMonth('check_in_time', $currentMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year        
                ->orderBy('student_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

            $attendanceTimeOut = $queryTimeOut
                ->whereMonth('check_out_time', $currentMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('student_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();
            }
            
            

                session()->flash('success', 'Attendance Report downloaded successfully!');
                $pdf = \PDF::loadView('generate-pdf-student', [
                'selectedStartDate' => $this->startDate,
                'selectedEndDate' => $this->endDate,
                'selectedMonth' => $currentMonth,
                'selectedYear' => $currentYear,
                'attendanceTimeIn' => $attendanceTimeIn,
                'attendanceTimeOut' => $attendanceTimeOut,
                'selectedEmployeeToShow' => $this->selectedEmployeeToShow,
            ])->setPaper('legal', 'landscape'); // Set paper size and orientation

             $pdf->save($savePath . '/' . $filename);

            // Download the PDF file with the given filename
           
            
            return response()->download($savePath . '/' . $filename, $filename);
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            dd($e->getMessage()); // Output the error for debugging
        }
    }
    // protected function applySearchFilters($query)
    // {
    //     return $query->where(function (Builder $query) {
    //         $query->where('student_id', 'like', '%' . $this->search . '%')
    //             ->orWhere('student_lastname', 'like', '%' . $this->search . '%')
    //             ->orWhere('student_firstname', 'like', '%' . $this->search . '%')
    //             ->orWhere('student_middlename', 'like', '%' . $this->search . '%')
    //             ->orWhere('student_year_grade', 'like', '%' . $this->search . '%')
    //             ->orWhere('student_rfid', 'like', '%' . $this->search . '%')
    //             ->orWhere('student_status', 'like', '%' . $this->search . '%')
    //             ->orWhereHas('course', function (Builder $query) {
    //                 $query->where('course_abbreviation', 'like', '%' . $this->search . '%')
    //                     ->orWhere('course_name', 'like', '%' . $this->search . '%');
    //             })
    //             ->orWhereHas('course.department', function (Builder $query) {
    //                 $query->where('department_abbreviation', 'like', '%' . $this->search . '%')
    //                     ->orWhere('department_name', 'like', '%' . $this->search . '%');
    //             });
    //     });
    // }
}
