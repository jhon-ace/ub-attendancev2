<?php

namespace App\Livewire\Admin;


use App\Models\Admin\EmployeeAttendanceTimeIn;
use App\Models\Admin\EmployeeAttendanceTimeOut;
use App\Models\Admin\School;
use App\Models\Admin\Department;
use App\Models\Admin\Employee;
use App\Models\Admin\GracePeriod;
use App\Models\Admin\DepartmentWorkingHour;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use App\Exports\AttendanceExportForPayroll;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DisplayDataforPayroll extends Component
{
    use WithPagination;

    public $search = '';
        public $searchh = '';
    public $sortField = 'employee_id';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment4 = "All Departments";
    public $selectedEmployee = null;
    public $departmentsToShow;
    public $schoolToShow;
    public $departmentToShow;
    public $attendancesToShow;
    public $selectedEmployeeToShow;
    public $startDate = null;
    public $endDate = null;
    public $selectedStartDate = null;
    public $selectedEndDate = null;
    public $selectedAttendanceByDate;
    public $selectedMonth;
    public $selectedYear;
    public $currentMonth;
    public $currentYear;
    public $groupedByMonth = [];
    public $selectedMonth2 = null;
    public $isAllDepartmentsSelected = false;
    public $years = [];
    


    protected $listeners = ['selectMonth','updateMonth', 'updateMonth2','updateEmployees', 'updateEmployeesByDepartment', 'updateAttendanceByEmployee', 'updateAttendanceByDateRange'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateDateRange()
    {
        $this->resetPage();
    }
    public function clearSearch()
    {
        $this->searchh = '';
    }
    
    public function clearSelection()
    {
        $this->selectedMonth2= '';
    }

    public function mount()
    {

        if (Auth::check() && Auth::user()->school) {
            $this->selectedSchool = Auth::user()->school->id;
        }

        $this->selectedMonth = now()->month;
            // Reset the selected value to zero first
        $this->selectedMonth2 = 0;

        // Now update the value with the selected key
        $this->selectedMonth2 = $this->selectedMonth2;
        $this->groupedByMonth = [];
        $this->years = range(2024, 2050);
        $this->selectedYear = now()->year;

        // $this->selectedSchool = session('selectedSchool', null);
        $this->selectedDepartment4 = session('selectedDepartment4', null);
        $this->selectedEmployee = session('selectedEmployee', null);
        $this->departmentsToShow = collect([]);
        $this->schoolToShow = collect([]);
        $this->departmentToShow = collect([]);
        $this->attendancesToShow = collect([]);
        $this->selectedEmployeeToShow = collect([]);
        $this->selectedAttendanceByDate = collect([]);
    }

    public function updateYear()
    {
        if ($this->selectedYear) {
            $this->attendancesToShow = EmployeeAttendanceTimeIn::where('employee_id', $this->selectedEmployee)
                ->where('status', '!=', 'Holiday')
                ->whereYear('check_in_time', $this->selectedYear) // Filter by selected year
                ->whereMonth('check_in_time', $this->selectedMonth) // Filter by selected month
                ->get();

           

            // Fetch corresponding time-out records
            $this->attendancesToShow = EmployeeAttendanceTimeOut::where('employee_id', $this->selectedEmployee)
                ->where('status', '!=', 'Holiday')
                ->whereYear('check_out_time', $this->selectedYear) // Filter by selected year
                ->whereMonth('check_out_time', $this->selectedMonth) // Filter by selected month
                ->get();

        } else {
            $this->selectedYear = now()->year;
        }
    }

    public function updateMonth()
    {   
        
        if ($this->selectedMonth && $this->startDate && $this->endDate) {
            // Get time-in records for the selected employee and month
            $this->attendancesToShow = EmployeeAttendanceTimeIn::where('employee_id', $this->selectedEmployee)
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_in_time', $this->selectedMonth) // Filter by selected month
                ->get();

            // Fetch corresponding time-out records
            $this->attendancesToShow = EmployeeAttendanceTimeOut::where('employee_id', $this->selectedEmployee)
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_out_time', $this->selectedMonth) // Filter by selected month
                ->get();

        } else if($this->selectedMonth){
            $this->attendancesToShow = EmployeeAttendanceTimeIn::where('employee_id', $this->selectedEmployee)
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_in_time', $this->selectedMonth) // Filter by selected month
                ->get();

            // Fetch corresponding time-out records
            $this->attendancesToShow = EmployeeAttendanceTimeOut::where('employee_id', $this->selectedEmployee)
                ->where('status', '!=', 'Holiday')
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




    public function updateMonth2()
    {   
            
        
        if ($this->selectedMonth2) {
     
            // $employeesWithLeaves = EmployeeAttendanceTimeIn::select(
            //     'employees_time_in_attendance.employee_id',
            //     'employees.employee_firstname',
            //     'employees.employee_lastname', 
            //     \DB::raw('GROUP_CONCAT(DATE(employees_time_in_attendance.check_in_time) ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_dates'),
            //     \DB::raw('GROUP_CONCAT(employees_time_in_attendance.check_in_time ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_times'),
            //     \DB::raw('GROUP_CONCAT(employees_time_in_attendance.status ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_statuses'),
            //     \DB::raw('GROUP_CONCAT(employees_time_out_attendance.check_out_time ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_times'),
            //     \DB::raw('GROUP_CONCAT(employees_time_out_attendance.status ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_statuses')
            // )
            // ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
            // ->leftJoin('employees_time_out_attendance', function ($join) {
            //     $join->on('employees_time_in_attendance.employee_id', '=', 'employees_time_out_attendance.employee_id')
            //         ->on(\DB::raw('DATE(employees_time_in_attendance.check_in_time)'), '=', \DB::raw('DATE(employees_time_out_attendance.check_out_time)'));
            // })
            // ->whereIn('employees_time_in_attendance.status', ['On Leave', 'Official Travel'])
            // // Use selected month if set
            // ->whereMonth('employees_time_in_attendance.check_in_time', '=', $this->selectedMonth2)
            // ->whereYear('employees_time_in_attendance.check_in_time', '=', $this->selectedYear) // Optional: add the year filter if necessary
            // ->orWhere('employees.department_id', '=', $this->selectedDepartment4)
            // ->groupBy('employees_time_in_attendance.employee_id', 'employees.employee_firstname', 'employees.employee_lastname') 
            // ->orderBy('employees.employee_lastname', 'asc')
            // ->orderBy('check_in_times', 'asc')
            // ->get();

            $employeesWithLeaves = EmployeeAttendanceTimeIn::select(
                    'employees_time_in_attendance.employee_id',
                    'employees.employee_firstname',
                    'employees.employee_lastname', 
                    \DB::raw('GROUP_CONCAT(DATE(employees_time_in_attendance.check_in_time) ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_dates'),
                    \DB::raw('GROUP_CONCAT(employees_time_in_attendance.check_in_time ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_times'),
                    \DB::raw('GROUP_CONCAT(employees_time_in_attendance.status ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_statuses'),
                    \DB::raw('GROUP_CONCAT(employees_time_out_attendance.check_out_time ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_times'),
                    \DB::raw('GROUP_CONCAT(employees_time_out_attendance.status ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_statuses')
                )
                ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
                ->leftJoin('employees_time_out_attendance', function ($join) {
                    $join->on('employees_time_in_attendance.employee_id', '=', 'employees_time_out_attendance.employee_id')
                        ->on(\DB::raw('DATE(employees_time_in_attendance.check_in_time)'), '=', \DB::raw('DATE(employees_time_out_attendance.check_out_time)'));
                })
                ->where(function ($query) {
                    $query->whereIn('employees_time_in_attendance.status', ['On Leave', 'Official Travel'])
                        ->where('employees.department_id', '=', $this->selectedDepartment4);
                })
                ->whereMonth('employees_time_in_attendance.check_in_time', '=', $this->selectedMonth2)
                ->whereYear('employees_time_in_attendance.check_in_time', '=', $this->selectedYear)
                ->groupBy('employees_time_in_attendance.employee_id', 'employees.employee_firstname', 'employees.employee_lastname') 
                ->orderBy('employees.employee_lastname', 'asc')
                ->orderBy('check_in_times', 'asc')
                ->get();


            $processedData = [];

            // Group and process data
            foreach ($employeesWithLeaves as $employee) {
                $checkInTimes = explode(', ', $employee->check_in_times);
                $checkInStatuses = explode(', ', $employee->check_in_statuses);
                $checkOutTimes = explode(', ', $employee->check_out_times);
                $checkOutStatuses = explode(', ', $employee->check_out_statuses);

                $groupedTimes = [];
                foreach ($checkInTimes as $index => $checkInTime) {
                    $date = \Carbon\Carbon::parse($checkInTime)->format('Y-m-d');
                    if (isset($checkOutTimes[$index]) && \Carbon\Carbon::parse($checkOutTimes[$index])->format('Y-m-d') == $date) {
                        $groupedTimes[$date] = [
                            'check_in_time' => $checkInTime,
                            'check_in_status' => $checkInStatuses[$index],
                            'check_out_time' => $checkOutTimes[$index],
                            'check_out_status' => $checkOutStatuses[$index],
                        ];
                    }
                }

                $processedData[] = [
                    'employee_name' => $employee->employee_lastname .', '. $employee->employee_firstname,
                    'employee_id' => $employee->employee_id,
                    'times' => array_values($groupedTimes),
                ];
            }

            // Debug processed data before filtering
       

   
            foreach ($processedData as $employeeData) {
                foreach ($employeeData['times'] as $time) {
                    $checkInDate = \Carbon\Carbon::parse($time['check_in_time']);
                    $month = $checkInDate->format('F Y');
                    $monthNumber = $checkInDate->month;

                    if ($this->selectedMonth2 && $monthNumber != $this->selectedMonth2) {
                        continue; // Skip if the month doesn't match
                    }

                    if (!isset($this->groupedByMonth[$month])) {
                        $this->groupedByMonth[$month] = [];
                    }

                    $this->groupedByMonth[$month][$employeeData['employee_name']] = [
                        'employee_id' => $employeeData['employee_id'],
                        'times' => $employeeData['times'],
                    ];
                }
            }

            // Debug grouped data by month
         
        } else {
     
           
        }

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
        $this->selectedEmployee = null;
        $this->selectedEmployeeToShow = null;
        $this->updateEmployeesByDepartment();
        $this->updateAttendanceByEmployee();
        $this->selectedEmployeeToShow = collect([]);
        $this->selectedAttendanceByDate = collect([]);
    }

    public function updatingSelectedEmployee()
    {
        $this->resetPage();
        $this->selectedAttendanceByDate = collect([]); // Reset selectedAttendanceByDate
        $this->startDate = null; // Reset start date
        $this->endDate = null; // Reset end date
        $this->updateAttendanceByEmployee();
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

    public function render()
    {

        
            // Check if the month is already in the array before adding it
            
        
        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => date('F', mktime(0, 0, 0, $month, 1))];
        });

        
        // Base query for EmployeeAttendanceTimeIn with left join to EmployeeAttendanceTimeOut
        $queryTimeIn = EmployeeAttendanceTimeIn::query()
            ->with(['employee.school', 'employee.department']);

        $queryTimeOut = EmployeeAttendanceTimeOut::query()
            ->with(['employee.school', 'employee.department']);
            

        // Apply search filters
        $queryTimeIn = $this->applySearchFiltersIn($queryTimeIn);
        $queryTimeOut = $this->applySearchFiltersOut($queryTimeOut);

        // Apply selected school filter
        if ($this->selectedSchool) {
            $queryTimeIn->whereHas('employee', function (Builder $query) {
                $query->where('school_id', $this->selectedSchool);
            });
            $queryTimeOut->whereHas('employee', function (Builder $query) {
                $query->where('school_id', $this->selectedSchool);
            });
            $this->schoolToShow = School::find($this->selectedSchool);
        } else {
            $this->schoolToShow = null;
        }

        // Apply selected department filter
        // if ($this->selectedDepartment4) {
        //     $queryTimeIn->whereHas('employee', function (Builder $query) {
        //         $query->where('department_id', $this->selectedDepartment4);
        //     });
        //     $queryTimeOut->whereHas('employee', function (Builder $query) {
        //         $query->where('department_id', $this->selectedDepartment4);
        //     });
        //     $this->departmentToShow = Department::find($this->selectedDepartment4);
        //     $employees = Employee::where('department_id', $this->selectedDepartment4)->get();
        // } else {
        //     $this->departmentToShow = null;
        //     $employees = Employee::all();
        // }

        // Number of records per page
        $perPage = 500;

        // if($this->selectedDepartment4 == "All Departments" && $this->selectedSchool) {

        // } else
        
        if ($this->selectedDepartment4) {
            // Get employees in the selected department with pagination
            $employees = Employee::where('department_id', $this->selectedDepartment4)->paginate(25);

            // Get all employee IDs in the selected department
            $employeeIds = $employees->pluck('id')->toArray();

            // Filter time-in and time-out records by department and employee IDs
            $queryTimeIn->whereIn('employee_id', $employeeIds)
                        ->whereHas('employee', function (Builder $query) {
                            $query->where('department_id', $this->selectedDepartment4);
                        });

            $queryTimeOut->whereIn('employee_id', $employeeIds)
                        ->whereHas('employee', function (Builder $query) {
                            $query->where('department_id', $this->selectedDepartment4);
                        });

            // $queryTimeIn->whereIn('employee_id', $employeeIds)
            //     ->whereHas('employee', function (Builder $query) {
            //         $query->where('department_id', $this->selectedDepartment4);
            //     })
            //     ->whereDay('check_in_time', '>=', 1)
            //     ->whereDay('check_in_time', '<=', 31);

            // $queryTimeOut->whereIn('employee_id', $employeeIds)
            //     ->whereHas('employee', function (Builder $query) {
            //         $query->where('department_id', $this->selectedDepartment4);
            //     })
            //     ->whereDay('check_out_time', '>=', 1)
            //     ->whereDay('check_out_time', '<=', 31);


            // Paginate the time-in and time-out records
            // $this->attendanceTimeIn = $queryTimeIn->paginate($perPage);
            // $this->attendanceTimeOut = $queryTimeOut->paginate($perPage);

            // Get department details
            $this->departmentToShow = Department::find($this->selectedDepartment4);
                        
            // Reset selected employee
            $this->selectedEmployeeToShow = null;
        } else {
            // No department selected, show all employees with pagination
            $this->departmentToShow = null;
            $employees = Employee::paginate($perPage);

            // Reset pagination for time-in and time-out records
            $this->attendanceTimeIn = $queryTimeIn->paginate($perPage);
            $this->attendanceTimeOut = $queryTimeOut->paginate($perPage);

            $this->selectedEmployeeToShow = null;
        }

        // Pass the paginated employees to the view
        $this->employees = $employees;



        // Apply selected employee filter
        // if ($this->selectedEmployee) {
        //     $queryTimeIn->where('employee_id', $this->selectedEmployee);
        //     $this->selectedEmployeeToShow = Employee::find($this->selectedEmployee);
        //     $queryTimeOut->where('employee_id', $this->selectedEmployee);
        //     $this->selectedEmployeeToShow = Employee::find($this->selectedEmployee);
        // } else {
        //     $this->selectedEmployeeToShow = null;
        // }


        // Apply date range filter if both dates are set
        if ($this->startDate && $this->endDate) {

            $currentMonth = $this->selectedMonth;  // Get the current month
            
            $currentYear = $this->selectedYear;  

             $queryTimeIn->whereDay('check_in_time', '>=', $this->startDate)
                        ->whereDay('check_in_time', '<=', $this->endDate);

            $queryTimeOut->whereDay('check_out_time', '>=', $this->startDate)
                        ->whereDay('check_out_time', '<=', $this->endDate);
                        
            $selectedAttendanceByDate = $queryTimeIn->get();// Fetch data and assign to selectedAttendanceByDate
            
            $this->selectedAttendanceByDate = $selectedAttendanceByDate;  

                    // $this->dispatch('reload-success');  



            $attendanceTimeIn = $queryTimeIn
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_in_time', $currentMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year        
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

            $attendanceTimeOut = $queryTimeOut
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_out_time', $currentMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();
            

        } else {
            $queryTimeIn->whereDay('check_in_time', '>=', 1)
                        ->whereDay('check_in_time', '<=', 31);

            $queryTimeOut->whereDay('check_out_time', '>=', 1)
                        ->whereDay('check_out_time', '<=', 31);

            $currentMonth = $this->selectedMonth;  // Get the current month
            $currentYear = $this->selectedYear;    // Get the current year

            $attendanceTimeIn = $queryTimeIn
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_in_time', $currentMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year        
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

            $attendanceTimeOut = $queryTimeOut
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_out_time', $currentMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();
        }

        
        $currentMonth = $this->selectedMonth;  // Get the current month
        $currentYear = now()->year; 

        $attendanceData = [];
        $overallTotalHours = 0;
        $overallTotalLateHours = 0;
        $overallTotalUndertime = 0;
        $totalHoursTobeRendered = 0;
        $overallTotalHoursSum = 0;

        
        foreach ($attendanceTimeIn as $attendance) {
            // Initialize variables for each record
          
            $hoursWorkedAM = 0;
            $hoursWorkedPM = 0;
            $lateDurationAM = 0;
            $lateDurationPM = 0;
            $undertimeAM = 0;
            $undertimePM = 0;
            $totalHoursLate = 0;
            $totalUndertimeHours = 0;
            $totalLateandUndertime = 0;
            $latePM = 0;
            $lateAM = 0;
            $undertimeAMTotal = 0;
            $undertimePMTotal = 0;
            $totalundertime = 0;
            $totalhoursNeed = 0;
            $totalHoursNeedperDay = 0;
            $overtimeHours = 0;
            $overtimeMinutes = 0;
            $ototalHours = 0;
            $ototalMinutes = 0;



            $totalHoursByDay = [];
            $overallTotalHoursSumm = 0;
            
            $departmentId = $attendance->employee->department_id;

            $workingHoursByDay = DepartmentWorkingHour::select(
                    'day_of_week',
                    'morning_start_time',
                    'morning_end_time',
                    'afternoon_start_time',
                    'afternoon_end_time'
                )
                ->where('department_id', $departmentId)
                ->where('day_of_week', '!=', 0)
                ->get()
                ->groupBy('day_of_week');

 

            foreach ($workingHoursByDay as $dayOfWeek => $workingHours) {
                $totalHours = 0;

                foreach ($workingHours as $workingHour) {
                    if ($workingHour->morning_start_time && $workingHour->morning_end_time) {
                        $morningStart = Carbon::parse($workingHour->morning_start_time);
                        $morningEnd = Carbon::parse($workingHour->morning_end_time);
                        $totalHours += $morningStart->diffInHours($morningEnd);
                        
                    }

                    if ($workingHour->afternoon_start_time && $workingHour->afternoon_end_time) {
                        $afternoonStart = Carbon::parse($workingHour->afternoon_start_time);
                        $afternoonEnd = Carbon::parse($workingHour->afternoon_end_time);
                        $totalHours += $afternoonStart->diffInHours($afternoonEnd);
                    }
                }

                $totalHoursByDay[$dayOfWeek] = $totalHours;
                $overallTotalHoursSumm += $totalHours;
            }

            // foreach ($totalHoursByDay as $dayOfWeek => $totalHours) {
            //     echo "Day of Week: $dayOfWeek\n";
            //     echo "Total Working Hours: $totalHours hours\n";
            //     echo "------------------------\n";
            // }
            // echo "Overall Total Working Hours: $overallTotalHours hours\n";

            $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
            // Extract date and time from check-in


            // Find corresponding check-out time
            $checkOut = $attendanceTimeOut->where('employee_id', $attendance->employee_id)
                                            ->where('check_out_time', '>=', $attendance->check_in_time)
                                            ->first();

            // $checkOuts = $attendanceTimeOut->where('employee_id', $attendance->employee_id)
            //                    ->where('check_out_time', '>=', $attendance->check_in_time)
            //                    ->orderBy('check_out_time', 'asc') // Ensure proper ordering
            //                    ->get();

            // // Get the second check-out time if it exists
            // $secondCheckOut = $checkOuts->skip(1)->first(); 
            // dd($secondCheckOut);

            if ($checkOut) {
                $checkOutDateTime = new DateTime($checkOut->check_out_time);



                // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                //                                                 ->where('day_of_week', '!=', 6)
                //                                                 ->first();

                    $checkInDateTime = new DateTime($attendance->check_in_time);
                    $checkInDate = $checkInDateTime->format('Y-m-d');
                    $checkInTime = $checkInDateTime->format('H:i:s'); // Extracted time part
                    $dayOfWeek = $checkInDateTime->format('w');

                // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                //                                                 ->where('day_of_week', '!=', 0)
                //                                                 ->first();

                $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                                                ->where('day_of_week', $dayOfWeek)
                                                ->where('day_of_week', '!=', 0)
                                                ->first();

                
                
               

                
                                

                
                if ($departmentWorkingHour) 
                {   

                
                    $mS = $departmentWorkingHour->morning_start_time;
                    $morningStartTime = clone $checkInDateTime;
                    $morningStartTime->setTime(
                        (int) date('H', strtotime($mS)),
                        (int) date('i', strtotime($mS)),
                        (int) date('s', strtotime($mS))
                    );

                    $morStart = $morningStartTime->setTime(
                        (int) date('H', strtotime($mS)),
                        (int) date('i', strtotime($mS)),
                        (int) date('s', strtotime($mS))
                    );

                    $mE = $departmentWorkingHour->morning_end_time;
                    $morningEndTime = clone $checkInDateTime;
                    $morningEndTime->setTime(
                        (int) date('H', strtotime($mE)),
                        (int) date('i', strtotime($mE)),
                        (int) date('s', strtotime($mE))
                    );

                    $aS = $departmentWorkingHour->afternoon_start_time;
                    $afternoonStartTime = clone $checkInDateTime;
                    $afternoonStartTime->setTime(
                        (int) date('H', strtotime($aS)),
                        (int) date('i', strtotime($aS)),
                        (int) date('s', strtotime($aS))
                    );

                    
                    $aE = $departmentWorkingHour->afternoon_end_time;
                        $afternoonEndTime = clone $checkInDateTime;
                        $afternoonEndTime->setTime(
                            (int) date('H', strtotime($aE)),
                            (int) date('i', strtotime($aE)),
                            (int) date('s', strtotime($aE))
                        );
                    
                    $morningStartTimew = $departmentWorkingHour->morning_start_time;
                    $morningEndTimew = $departmentWorkingHour->morning_end_time;
                    $afternoonStartTimew = $departmentWorkingHour->afternoon_start_time;
                    $afternoonEndTimew = $departmentWorkingHour->afternoon_end_time;

                        // Convert times to Carbon instances
                    $morningStartw = new DateTime($morningStartTimew);
                    $morningEndw = new DateTime($morningEndTimew);
                    $afternoonStartw = new DateTime($afternoonStartTimew);
                    $afternoonEndw = new DateTime($afternoonEndTimew);

                               $hoursOvertime = 0;
                                $minutesOvertime = 0;
                    
    
                    

                 


                    

                    // Calculate the duration in minutes for morning and afternoon
                    $morningInterval = $morningStartw->diff($morningEndw);
                    $morningDurationInMinutes = ($morningInterval->h * 60) + $morningInterval->i;
                    $afternoonInterval = $afternoonStartw->diff($afternoonEndw);
                    $afternoonDurationInMinutes = ($afternoonInterval->h * 60) + $afternoonInterval->i;

                    // Convert minutes to hours
                    $morningDuration = $morningDurationInMinutes / 60;
                    $afternoonDuration = $afternoonDurationInMinutes / 60;
                    // Calculate total hours needed
                    $totalHoursNeed = $morningDuration + $afternoonDuration;
                    $totalHoursTobeRendered = $totalHoursNeed;
                    $totalHoursNeedperDay = $totalHoursNeed;
                    if ($this->startDate && $this->endDate) {
                        $employeeId = $attendance->employee_id; // Assuming you have this from $attendance

                        // Determine if the start date and end date are the same
                        $isSameDate = $this->startDate === $this->endDate; // Adjust if necessary for your date format
                        //$startDate = Carbon::parse($this->startDate)->startOfDay(); // Start of the selected start date
                        $startDate = Carbon::parse("{$currentYear}-{$currentMonth}-{$this->startDate}")->startOfDay();
                        // $endDate = Carbon::parse($this->endDate)->endOfDay(); // End of the selected end date
                        $endDate = Carbon::parse("{$currentYear}-{$currentMonth}-{$this->endDate}")->startOfDay();

                        if ($isSameDate) {
                            // If the start date and end date are the same, only consider that specific day
                            $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                                ->where('employee_id', $employeeId)
                                ->whereDay('check_in_time', $this->startDate)
                                ->first();
                        } else {
                            // If the start date and end date are different, consider the range
                            // $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                            //     ->where('employee_id', $employeeId)
                            //     ->whereBetween('check_in_time', [$startDate, $endDate])
                            //     ->first();
                            $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(employees_time_in_attendance.check_in_time)) as unique_check_in_days'))
                                        ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
                                        ->join('working_hour', function($join) {
                                            $join->on('employees.department_id', '=', 'working_hour.department_id');
                                        })
                                        ->where('employees_time_in_attendance.employee_id', $employeeId)
                                        ->whereNotIn('employees_time_in_attendance.status', ['Absent', 'AWOL', 'On Leave'])
                                        ->whereNotIn('working_hour.day_of_week', [0, 6])
                                        ->whereBetween('check_in_time', [$startDate, $endDate]) // Exclude Saturday (6) and Sunday (7)
                                        ->first();
                                    

                        }

                        // Get the unique check-in days count
                        $uniqueCheckInDays = (int) $checkInCount->unique_check_in_days;
                        
                        // Calculate total hours to be rendered
                        $totalHoursTobeRendered = $totalHoursNeed * $uniqueCheckInDays;
                    } else {
                        
                        $employeeId = $attendance->employee_id; // Assuming you have this from $attendance
                        

                        $noww = new DateTime('now', new DateTimeZone('Asia/Taipei'));
                        $currentDatee = $noww->format('Y-m-d') . ' 00:00:00';

                        $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                            ->where('employee_id', $employeeId)
                            ->where('check_in_time', '<>', $currentDatee)
                            ->whereNotIn('status', ['Absent', 'AWOL', 'On Leave'])
                            ->first();

                        $uniqueCheckInDays = (int) $checkInCount->unique_check_in_days;
                        $totalHoursTobeRendered = $totalHoursNeed * $uniqueCheckInDays;

                   
                    }
                    
                    $gracePeriodFirst = GracePeriod::first();
                    if($gracePeriodFirst){
                        $gracePeriodValue = (float) $gracePeriodFirst->grace_period;
                        // AM Shift Calculation  for 15 mins interval of declaring late
                        if ($checkInDateTime < $morningEndTime) {
                            $effectiveCheckInTime = max($checkInDateTime, $morningStartTime);
                            $effectiveCheckOutTime = min($checkOutDateTime, $morningEndTime);
                            if ($effectiveCheckInTime < $effectiveCheckOutTime) {
                                $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60);
                                $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                
                                // Calculate late duration for AM
                                

                                // Check if there's only one check-in and check-out in the same day
                                // if ($checkInDateTime->format('Y-m-d') == $checkOutDateTime->format('Y-m-d')) {
                                //     $hoursWorkedAM = 0;
                                // }

                                $dateKey1 = $checkInDateTime->format('Y-m-d');
                                $dateKey2 = $checkOutDateTime->format('Y-m-d');

                                // Fetch counts of check-ins and check-outs for the specified dates
                                $checkOuttCount = EmployeeAttendanceTimeOut::select(DB::raw('DATE(check_out_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_out_time)'))
                                    ->pluck('count', 'date');

                                $checkInnCount = EmployeeAttendanceTimeIn::select(DB::raw('DATE(check_in_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_in_time)'))
                                    ->pluck('count', 'date');

                                // Fetch the first check-in and check-out times for the specified date
                                $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->first();

                                $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->first();

                                $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                // Calculate PM hours if applicable
                                if ($checkInnCount->get($dateKey1, 0) == 1 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                    
                                    if ($firstCheckIn && $firstCheckOut) {
                                        $checkInTime = new DateTime($firstCheckIn->check_in_time);
                                        $checkOutTime = new DateTime($firstCheckOut->check_out_time);

                                        // Ensure check-in is PM and check-out is also PM
                                        if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'pm') {
                                            $amEndTime = new DateTime($dateKey1 . ' 14:00:00');
                                            if ($checkOutTime < $amEndTime) {
                                            // Calculate the interval between the check-out time and 1:00 PM
                                            //so naa cutoff ang checkout sa buntag 1 pwedi i set $amEndTime
                                            // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                            
                                            $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);

                                            }else{
                                                $hoursWorkedAM = 0;
                                            }
                                        }
                                        else {
                                            // $hoursWorkedAM = 0;
                                        }
                                    } else {
                                        $hoursWorkedAM = 0;
                                    }
                                } else {
                                     
                                        if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                            if ($firstCheckIn && $firstCheckOut && $secondCheckIn && $secondCheckOut) {
                                                
                                                $checkInTime = new DateTime($firstCheckIn->check_in_time);
                                                $checkOutTime = new DateTime($firstCheckOut->check_out_time);

                                                $checkInTime2 = Carbon::parse($secondCheckIn->check_in_time);
                                                $checkOutTime2 = Carbon::parse($secondCheckOut->check_out_time);

                                                if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'am' && $checkInTime2->format('a') === 'pm' && $checkOutTime2->format('a') === 'am') { 
                                                    $hoursWorkedAM = 0;
                                                }
                                            }
                                        }
                                    

                                    // if ($checkInnCount->get($dateKey1, 0) == 1 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                    //     $hoursWorkedAM = 0;
                                    // }

                                }


                                if ($checkInDateTime > $morningStartTime) {
                                    // Define the latest allowed check-in time with a 15-minute grace period
                                    // $latestAllowedCheckInAM = clone $morningStartTime;
                                    // $latestAllowedCheckInAM->add(new DateInterval('PT15M'));
                                    // Rounds to nearest integer

                                    
                                    $gracePeriodMinutes = $gracePeriodValue * 60;
                                    $gracePeriodMinutes = round($gracePeriodMinutes);
                                    $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                    
                                    // Clone the original time and add the interval
                                    $latestAllowedCheckInAM = clone $morningStartTime;
                                    $latestAllowedCheckInAM->add(new DateInterval($intervalSpec));

                                    // Check if the check-in time is beyond the 15-minute grace period
                                    if ($checkInDateTime > $latestAllowedCheckInAM ) {

                                        // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        // $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                        // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        // $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                        // Calculate the late interval starting from the grace period end

                                        // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        // // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60);
                                        // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                        

                                        $lateIntervalAM = $checkInDateTime->diff($latestAllowedCheckInAM);
                                        $lateDurationAM = ($lateIntervalAM->h * 60) + $lateIntervalAM->i + ($lateIntervalAM->s / 60);
                                        
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $lateAM = $lateIntervalAM->h + ($lateIntervalAM->i / 60) + ($lateIntervalAM->s / 3600);
                                        
                                        


                                    } else {
                                        $lateIntervalAM = $checkInDateTime->diff($latestAllowedCheckInAM);
                                        $lateDurationAM = ($lateIntervalAM->h * 60) + $lateIntervalAM->i + ($lateIntervalAM->s / 60);
                                        
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $lateAM = $lateIntervalAM->h + ($lateIntervalAM->i / 60) + ($lateIntervalAM->s / 3600);
                                        
                                        // Calculate hours worked in the AM
                                        $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);

                                        // Calculate the difference in minutes (or hours) from $checkInDateTime to $morningStartTime
                                        $intervalToMorningStart = $checkInDateTime->diff($morningStartTime);
                                        $dataInMinutes = $intervalToMorningStart->h * 60 + $intervalToMorningStart->i + ($intervalToMorningStart->s / 60);
                                        $dataInHours = $dataInMinutes / 60;

                                        $hoursWorkedAM = $hoursWorkedAM + $dataInHours;
                                        
                                        $lateDurationAM = 0;
                                        $lateAM = 0;
                                        
                                    }
                                        
                                    // if($checkInDateTime < $latestAllowedCheckInAM)
                                    // {
                                        
                                    //     // $intervalAM = $morningStartTime->diff($effectiveCheckOutTime);
                                        
                                    //     // // Convert intervals to total minutes
                                    //     // $intervalMinutesAM = ($intervalAM->h * 60) + $intervalAM->i + ($intervalAM->s / 60);

                                    //     // // Calculate hours worked in AM
                                    //     // $hoursWorkedAM = $intervalMinutesAM / 60; // Convert total minutes to hours
                                    //     // $hoursWorkedAM += 0.25;
                                    //     // $lateDurationAM = 0;
                                    //     // $lateAM = 0;
                                        
                                    // }
                                } else {
                                    // If check-in is on time or early, set late duration to 0
                                        $lateDurationAM = 0;
                                        $lateAM = 0;
                                        
                                }

                                if ($lateDurationAM > 0 ) {
                                    // $hoursWorkedAM += 0.25; // Subtract 0.25 hours (15 minutes) if late
                                    
                                    $hoursWorkedAM += $gracePeriodValue;
                                    
                                }
                                // if ($lateDurationAM > 0 ) {
                                //     // $hoursWorkedAM += 0.25; // Subtract 0.25 hours (15 minutes) if late
                                    
                                //     $hoursWorkedAM += $gracePeriodValue;
                                    
                                // }

                                if ($lateDurationAM > 0 && $hoursWorkedAM == $gracePeriodValue) {
                                    $hoursWorkedAM -= $gracePeriodValue;
                                    $lateDurationAM = 0;
                                    $lateAM = 0; // Subtract 0.25 hours (15 minutes) if late
                                }

                                
                                // if($lateDurationAM > 0 && $hoursWorkedAM > $gracePeriodValue){
                                //          $hoursWorkedAM += $gracePeriodValue;
                                // }

                                // if ($lateDurationAM > 0 && $hoursWorkedAM == 0.25) {
                                //     $hoursWorkedAM -= 0.25;
                                //     $lateDurationAM = 0;
                                //     $lateAM = 0; // Subtract 0.25 hours (15 minutes) if late
                                // }   
                                
                                // if ($checkInDateTime <= $latestAllowedCheckInAM) {
                                //     // Calculate the total hours worked considering the effective check-in time and the morning end time
                                //     $effectiveCheckInTime = max($checkInDateTime, $morningStartTime);
                                //     $effectiveCheckOutTime = min($checkOutDateTime, $morningEndTime);
                                //     $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                //     $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                // }


                            }

                                $scheduledDiff = $morningStartTime->diff($morningEndTime);
                                $scheduledAMMinutes = ($scheduledDiff->h * 60) + $scheduledDiff->i + ($scheduledDiff->s / 60);

                                // Calculate actual worked time up to the morning end time including seconds
                                if ($effectiveCheckOutTime < $morningEndTime) {
                                    $actualDiff = $effectiveCheckOutTime->diff($morningStartTime);
                                } else {
                                    $actualDiff = $morningEndTime->diff($morningStartTime);
                                }
                                $actualMinutesUpToEnd = ($actualDiff->h * 60) + $actualDiff->i + ($actualDiff->s / 60);
                                    $undertimeAMTotal = max(0, $scheduledAMMinutes - $actualMinutesUpToEnd);
                                // Calculate undertime in minutes
                                $undertimeAM = max(0, $scheduledAMMinutes - $actualMinutesUpToEnd);
                    
                        }   
                    

                        // PM Shift Calculation
                        if ($checkInDateTime < $afternoonEndTime && $checkOutDateTime > $afternoonStartTime) {
                            $effectiveCheckInTime = max($checkInDateTime, $afternoonStartTime);
                            $effectiveCheckOutTime = min($checkOutDateTime, $afternoonEndTime);
                            if ($effectiveCheckInTime < $effectiveCheckOutTime) {
                                $intervalPM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                // $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                // Calculate late duration for PM
                                // $latestAllowedCheckInPM = clone $afternoonStartTime;
                                // $latestAllowedCheckInPM->add(new DateInterval('PT15M'));

                                    $gracePeriodMinutes = $gracePeriodValue * 60;
                                    $gracePeriodMinutes = round($gracePeriodMinutes);
                                    $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                    
                                    // Clone the original time and add the interval
                                    $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    $latestAllowedCheckInPM->add(new DateInterval($intervalSpec));

                                // Check if there's only one check-in and check-out in the same day
                                // if ($checkInDateTime->format('Y-m-d') == $checkOutDateTime->format('Y-m-d')) {
                                //     $hoursWorkedPM = 0;
                                // }

                                // Convert the check-in and check-out date to the format 'Y-m-d'
                                $dateKey1 = $checkInDateTime->format('Y-m-d');
                                $dateKey2 = $checkOutDateTime->format('Y-m-d');

                                // Fetch counts of check-ins and check-outs for the specified dates
                                $checkOuttCount = EmployeeAttendanceTimeOut::select(DB::raw('DATE(check_out_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_out_time)'))
                                    ->pluck('count', 'date');

                                $checkInnCount = EmployeeAttendanceTimeIn::select(DB::raw('DATE(check_in_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_in_time)'))
                                    ->pluck('count', 'date');

                                // Fetch the first check-in and check-out times for the specified date
                                $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->first();

                                $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->first();

                                $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                // Calculate PM hours if applicable
                                   
                                if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                    if ($secondCheckIn && $secondCheckOut) {
                                        // Convert check-in and check-out times to Carbon instances
                                        $checkInTime = Carbon::parse($secondCheckIn->check_in_time);
                                        $checkOutTime = Carbon::parse($secondCheckOut->check_out_time);

                                        // Ensure check-in is PM and check-out is also PM
                                        if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'pm') {
                                            $timeStartTimeSecond = Carbon::parse($afternoonStartTime)->format('H:i:s');
                                            $secondTime = Carbon::parse($secondCheckIn->check_in_time)->format('H:i:s');
                                            
                                            //dd($afternoonStartTime > $secondTime);
                                            // if($afternoonStartTime > $secondTime){
                                            //     $hoursWorkedPM = 0;
                                            // } else {
                                               $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                                // if($afternoonStartTime > $secondTime){
                                            //     $hoursWorkedPM = 0;
                                            // } else {
                                            // }
                                        } else {
                                            $hoursWorkedPM = 0;
                                        }
                                    } else {
                                        $hoursWorkedPM = 0;
                                    }
                                } else {
                                    // Set to 0 if counts are not both 2
                                        
                                    if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                        $hoursWorkedPM = 0;
                                    } 
                                    else 
                                    {
                                        if ($firstCheckIn && $firstCheckOut) {
                                            // Convert check-in and check-out times to Carbon instances
                                            $checkInTime = Carbon::parse($firstCheckIn->check_in_time);
                                            $checkOutTime = Carbon::parse($firstCheckOut->check_out_time);

                                            // Ensure check-in is PM and check-out is also PM
                                            if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'pm') {
                                                $hoursWorkedPM = 0;
                                            } else if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'am') {
                                                $hoursWorkedPM = 0;
                                            }else {
                                                $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);
                                            }
                                        }
                                    }
   
                                }



                                
                                if ($checkInDateTime > $afternoonStartTime) {

                                    // $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    // $latestAllowedCheckInPM->add(new DateInterval('PT15M'));

                                    $gracePeriodMinutes = $gracePeriodValue * 60;
                                    $gracePeriodMinutes = round($gracePeriodMinutes);
                                    $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                    
                                    // Clone the original time and add the interval
                                    $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    $latestAllowedCheckInPM->add(new DateInterval($intervalSpec));

                                    // $lateIntervalPM = $checkInDateTime->diff($afternoonStartTime);
                                    // $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                    // $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);

                                    // Check if the check-in time is beyond the 15-minute grace period
                                    if ($checkInDateTime > $latestAllowedCheckInPM ) {
                                        // Calculate the late interval starting from the grace period end
                                        $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                        $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                        

                                    } else {
                                        // If within the grace period, set late duration to 0
                                        // $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                        // $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        // // Calculate the late duration in hours, minutes, and seconds
                                        
                                        // $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                        // $lateDurationPM = 0;
                                        // $latePM = 0;

                                        $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                        $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                        
                                        // Calculate hours worked in the PM
                                        $intervalPM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        $hoursWork = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                        // Calculate the difference in minutes (or hours) from $checkInDateTime to $morningStartTime
                                        $intervalToAfternoonStart = $checkInDateTime->diff($afternoonStartTime);
                                        $dataInMinutes = $intervalToAfternoonStart->h * 60 + $intervalToAfternoonStart->i + ($intervalToAfternoonStart->s / 60);
                                        $dataInHours = $dataInMinutes / 60;

                                        if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                            if ($secondCheckIn && $secondCheckOut) {
                                                // Convert check-in and check-out times to Carbon instances
                                                $checkInTime = Carbon::parse($secondCheckIn->check_in_time);
                                                $checkOutTime = Carbon::parse($secondCheckOut->check_out_time);

                                                // Ensure check-in is PM and check-out is also PM
                                                if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'pm') {
                                                    $timeStartTimeSecond = Carbon::parse($afternoonStartTime)->format('H:i:s');
                                                    $secondTime = Carbon::parse($secondCheckIn->check_in_time)->format('H:i:s');
                                                    
                                                    //dd($afternoonStartTime > $secondTime);
                                                    // if($afternoonStartTime > $secondTime){
                                                    //     $hoursWorkedPM = 0;
                                                    // } else {
                                                  $hoursWorkedPM = $hoursWorkedPM + $dataInHours;

                                                        // if($afternoonStartTime > $secondTime){
                                                    //     $hoursWorkedPM = 0;
                                                    // } else {
                                                    // }
                                                } else {
                                                    $hoursWorkedPM = 0;
                                                }
                                            } else {
                                                $hoursWorkedPM = 0;
                                            }
                                        } else {
                                            // Set to 0 if counts are not both 2
                                            
                                            if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                                $hoursWorkedPM = 0;
                                            }
                                        }
                                                

                                                                              
                                        
                                         
                                        $lateDurationPM = 0;
                                        $latePM = 0;
                                        
                                        
                                    }

                                    // if($checkInDateTime <= $latestAllowedCheckInPM)
                                    // {
                                        
                                    //     $intervalPM = $afternoonStartTime->diff($effectiveCheckOutTime);
                                        
                                    //     // Convert intervals to total minutes
                                    //     $intervalMinutesPM = ($intervalPM->h * 60) + $intervalPM->i + ($intervalPM->s / 60);

                                    //     // Calculate hours worked in AM
                                    //     $hoursWorkedPM= $intervalMinutesPM / 60; // Convert total minutes to hours
                                        

                                    // }

                                } else {
                                    // If check-in is on time or early, set late duration to 0
                                        $lateDurationPM = 0;
                                        $latePM = 0;
                                        
                                }

                                if ($lateDurationPM > 0) {
                                    $hoursWorkedPM += $gracePeriodValue; // Subtract 0.25 hours (15 minutes) if late
    
                                }

                                if ($lateDurationPM > 0 && $hoursWorkedPM == $gracePeriodValue) {
                                    $hoursWorkedPM -= $gracePeriodValue;
                                    $lateDurationPM = 0;
                                    $latePM = 0; // Subtract 0.25 hours (15 minutes) if late
                                }

                                
                                

                                //  if ($lateDurationPM > 0) {
                                //     $hoursWorkedPM += 0.25; // Subtract 0.25 hours (15 minutes) if late
    
                                // }
                                
                            }

                            

                            $scheduledPMDiff = $afternoonStartTime->diff($afternoonEndTime);
                            $scheduledPMMinutes = ($scheduledPMDiff->h * 60) + $scheduledPMDiff->i + ($scheduledPMDiff->s / 60);

                            // Calculate actual worked time up to the afternoon end time including seconds
                            if ($effectiveCheckOutTime < $afternoonEndTime) {
                                $actualPMDiff = $effectiveCheckOutTime->diff($afternoonStartTime);
                            } else {
                                $actualPMDiff = $afternoonEndTime->diff($afternoonStartTime);
                            }
                            $actualMinutesUpToEndPM = ($actualPMDiff->h * 60) + $actualPMDiff->i + ($actualPMDiff->s / 60);
                            $undertimePMTotal = max(0, $scheduledPMMinutes - $actualMinutesUpToEndPM);
                            // Calculate undertime in minutes
                            $undertimePM = max(0, $scheduledPMMinutes - $actualMinutesUpToEndPM);

                        }
                    }



                    // Calculate total hours worked
                    $totalHoursWorked = $hoursWorkedAM + $hoursWorkedPM;
                    
                    
                    $totalHoursLate = $lateDurationAM + $lateDurationPM;
                    $totalUndertimeHours = $undertimeAM + $undertimePM;
                    $overallTotalHoursLate = $lateAM + $latePM;
                    $totalundertime = $undertimeAMTotal + $undertimePMTotal;

                    
                    // $totalhoursNeed = $morningDuration + $afternoonDuration;
    
                    // Determine remark based on lateness
                    $remark = ($lateDurationAM > 0 || $lateDurationPM > 0) ? 'Late' : 'Present';

                    $modifyStatus = $attendance->status;

                    $dateKey1 = $checkInDateTime->format('Y-m-d');
                    $dateKey2 = $checkOutDateTime->format('Y-m-d');

                    $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                        ->whereDate('check_in_time', $dateKey1)
                        ->orderBy('check_in_time', 'asc')
                        ->first();

                    $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                        ->whereDate('check_out_time', $dateKey2)
                        ->orderBy('check_out_time', 'asc')
                        ->first();

                    $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                        ->whereDate('check_in_time', $dateKey1)
                        ->orderBy('check_in_time', 'asc')
                        ->skip(1)
                        ->first();

                    $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                        ->whereDate('check_out_time', $dateKey2)
                        ->orderBy('check_out_time', 'asc')
                        ->skip(1)
                        ->first();
  

                    $modifyStatusFirstAM = $firstCheckIn;
                    $modifyStatusSecondAM = $firstCheckOut;
                    $modifyStatusFirstPM = $secondCheckIn;
                    $modifyStatusSecondPM = $secondCheckOut;

                    // Prepare the key for $attendanceData
                    $key = $attendance->employee_id . '-' . $checkInDate;

                     $employee_idd = $attendance->employee->employee_id;
                    $employee_id = $attendance->employee_id;
                    $employeeLastname = $attendance->employee->employee_lastname;
                    $employeeFirstname = $attendance->employee->employee_firstname;
                    $employeeMiddlename = $attendance->employee->employee_middlename;
                    $checkInTimer = $attendance->check_in_time;


                // $secondCheckOutsLists = EmployeeAttendanceTimeOut::orderBy('employee_id')
                //     ->orderBy('check_out_time', 'asc') // Ensure records are ordered by check-out time
                //     ->get() // Get all check-out records
                //     ->groupBy('employee_id') // Group by employee_id
                //     ->map(function ($checkOutsPerEmployee) {
                //         // Group by date by comparing only the date part of check_out_time
                //         return $checkOutsPerEmployee->groupBy(function ($checkOut) {
                //             // Use DateTime instead of Carbon to extract the date
                //             $checkOutTime = new DateTime($checkOut->check_out_time);
                //             return $checkOutTime->format('Y-m-d'); // Extract the date part only
                //         })
                //         ->map(function ($checkOutsPerDate) {
                //             // Skip the first check-out and get the second one for each date
                //             return $checkOutsPerDate->skip(1)->first(); // Skip the first check-out and get the second one
                //         })
                //         ->filter(); // Remove null values for dates with fewer than 2 check-outs
                //     });

                // $afternoonEndTimeww = new DateTime($afternoonEndTimew); // Keep this as DateTime object
            
                //     $ototalHour = 0;
                //     $ototalMinute = 0;

                // $checkOutDifferences = $secondCheckOutsLists->map(function ($checkOutsPerEmployee) use ($afternoonEndTimeww) {
                //         return $checkOutsPerEmployee->map(function ($secondCheckOut) use ($afternoonEndTimeww) {
                //             $checkOutTime = new DateTime($secondCheckOut->check_out_time); // Convert check-out time to DateTime

                //             // Calculate the difference in seconds
                //             $interval = $afternoonEndTimeww->diff($checkOutTime);

                //             // Return the difference in a formatted way (e.g., hours and minutes)
                //             return [
                //                 'check_out_time' => $checkOutTime->format('Y-m-d H:i:s'),
                //                 'difference_in_minutes' => ($interval->h * 60) + $interval->i, // Convert to minutes
                //             ];
                //         });
                //     });

                //     $differencesInMinutes = [];

                //     foreach ($checkOutDifferences as $checkOutsPerEmployee) {
                //         foreach ($checkOutsPerEmployee as $tot) {
                //             // Store each difference_in_minutes value
                //             $differencesInMinutes[] = $tot['difference_in_minutes'];
                //         }
                //     }

                //     // Now store the accumulated differences_in_minutes array in the session
         
                    $oTotalHour = 0;
                    $oTotalMinute = 0;

                    $firstCheckOutt = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                        ->whereDate('check_out_time', $dateKey2)
                        ->orderBy('check_out_time', 'asc')
                        ->first();

                    $secondCheckOutr = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                        ->whereDate('check_out_time', $dateKey2)
                        ->orderBy('check_out_time', 'asc')
                        ->skip(1)
                        ->first();


                    if ($firstCheckOutt && $secondCheckOutr) 
                    {

                        //     // To retrieve and use the session data later
                        if ($checkOutDateTime > $afternoonEndw) {
                                $timeAfterEnd = $checkOutDateTime->diff($afternoonEndw);

                                // Format the result in hours and minutes
                                $oTotalHour = $timeAfterEnd->h; // Hours
                                $oTotalMinute = $timeAfterEnd->i; // Minutes
                            } 

                            // Adjust `afternoonEndw` for previous days as needed
       
                                
                                while($checkOutDateTime < $afternoonEndw) {

                                    // Move `afternoonEndw` back one day
                                    $afternoonEndw = (clone $afternoonEndw)->modify('-1 day');
                                    
                                }
                                   
                            

                            // Check if `checkOutDateTime` exceeds the adjusted `afternoonEndw`
                            if ($checkOutDateTime > $afternoonEndw) {
                                
                                $timeAfterEnd = $checkOutDateTime->diff($afternoonEndw);

                                // Format the result in hours and minutes
                                $oTotalHour = $timeAfterEnd->h;
                                $oTotalMinute = $timeAfterEnd->i;
                            }
                    }  else {
                            $oTotalHour = 0;
                            $oTotalMinute = 0;
                    }
                 

                        // Check if this entry already exists in $attendanceData
                        if (isset($attendanceData[$key])) {
                            // Update existing entry
                  
                            $attendanceData[$key]->hours_perDay = $totalHoursNeedperDay;
                            $attendanceData[$key]->hours_workedAM += $hoursWorkedAM;
                            $attendanceData[$key]->hours_workedPM = $hoursWorkedPM;
                            $attendanceData[$key]->total_hours_worked += $totalHoursWorked;
                            $attendanceData[$key]->total_hours_late += $totalHoursLate;
                            $attendanceData[$key]->late_duration += $lateDurationAM;
                            $attendanceData[$key]->late_durationPM += $lateDurationPM;
                            $attendanceData[$key]->undertimeAM += $undertimeAM;
                            $attendanceData[$key]->undertimePM += $undertimePM;
                            $attendanceData[$key]->total_late += $totalHoursLate;
                            $attendanceData[$key]->remarks = $remark;
                            $attendanceData[$key]->modify_status = $modifyStatus;
                            $attendanceData[$key]->employee_idd = $employee_idd;
                            $attendanceData[$key]->employee_id = $employee_id;
                            $attendanceData[$key]->employee_lastname = $employeeLastname;
                            $attendanceData[$key]->employee_firstname = $employeeFirstname;
                            $attendanceData[$key]->employee_middlename = $employeeMiddlename;
                            $attendanceData[$key]->hours_late_overall += $overallTotalHoursLate;
                            $attendanceData[$key]->hours_undertime_overall += $totalundertime;
                            $attendanceData[$key]->check_in_time = $checkInTimer;
                            $attendanceData[$key]->firstCheckInStatus = $firstCheckIn ? $firstCheckIn->status : null;
                            $attendanceData[$key]->firstCheckOutStatus = $firstCheckOut ? $firstCheckOut->status : null;
                            $attendanceData[$key]->secondCheckInStatus = $secondCheckIn ? $secondCheckIn->status : null;
                            $attendanceData[$key]->secondCheckOutStatus = $secondCheckOut ? $secondCheckOut->status : null;

                            // Add ototalHour and ototalMinute
                            $attendanceData[$key]->overtime_hours = $oTotalHour;
                            $attendanceData[$key]->overtime_minutes = $oTotalMinute;
                

                            // dd($attendanceData[$key]->total_hours_worked += $totalHoursWorked;);
                        } else {
                            // Create new entry
                            $attendanceData[$key] = (object) [
                                'hours_perDay' => $totalHoursNeedperDay,
                                'employee_id' => $attendance->employee_id,
                                'employee_lastname' => $employeeLastname,
                                'employee_firstname' => $employeeFirstname,
                                'employee_middlename' => $employeeMiddlename,
                                'worked_date' => $checkInDate,
                                'hours_workedAM' => $hoursWorkedAM,
                                'hours_workedPM' => $hoursWorkedPM,
                                'total_hours_worked' => $totalHoursWorked,
                                'total_hours_late' => $totalHoursLate,
                                'late_duration' => $lateDurationAM,
                                'late_durationPM' => $lateDurationPM,
                                'undertimeAM' => $undertimeAM,
                                'undertimePM' => $undertimePM,
                                'total_late' => $totalHoursLate,
                                'remarks' => $remark,
                                'modify_status'=> $modifyStatus,
                                'hours_late_overall' => $overallTotalHoursLate,
                                'hours_undertime_overall' => $totalundertime,
                                'check_in_time' => $checkInTimer,
                                'employee_idd' => $employee_idd,
                                'firstCheckInStatus' => $firstCheckIn->status ?? '',
                                'firstCheckOutStatus' => $firstCheckOut->status ?? '',
                                'secondCheckInStatus' => $secondCheckIn->status ?? '',
                                'secondCheckOutStatus' => $secondCheckOut->status ?? '',

                                'overtime_hours' => $oTotalHour,
                                'overtime_minutes' => $oTotalMinute,

                            ];

                            //  session()->put('late_duration', $lateDurationAM);
                        }
                    
                    

                    // Add total hours worked to overall total
                    $overallTotalHours += $totalHoursWorked;
                    $overallTotalLateHours += $overallTotalHoursLate;
                    $overallTotalUndertime += $totalundertime;

                    // $overtimeHours += $totalHours;
                  
                }
            }
        }

        // Optionally, you can store the $attendanceData and $overallTotalHours in the session or pass it to a view
        session()->put('attendance_data', $attendanceData);

        session()->put('overall_total_hours', $overallTotalHours);

        // dd($attendanceData);



        $schools = School::all();
        $departments = Department::where('school_id', $this->selectedSchool)
            ->whereIn('dept_identifier', ['employee', 'faculty'])
            ->orderBy('department_abbreviation', 'asc')
            ->get();


        $departmentDisplayWorkingHour = DepartmentWorkingHour::where('department_id', $this->selectedDepartment4)
                                                           ->get();

        $holidays = EmployeeAttendanceTimeIn::where('status', 'Holiday')
            ->select(DB::raw('DATE(check_in_time) as check_in_date')) // Extract only the date
            ->groupBy('check_in_date') // Group by the extracted date
            ->orderBy('check_in_date', 'asc') // Order by date (ascending)
            ->get();

//         $employeesWithLeaves = EmployeeAttendanceTimeIn::select(
//                 'employees_time_in_attendance.employee_id', 
//                 'employees.employee_lastname', 
//                 \DB::raw('GROUP_CONCAT(DATE(employees_time_in_attendance.check_in_time) ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_dates'),
//                 \DB::raw('GROUP_CONCAT(employees_time_in_attendance.check_in_time ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_times'),
//                 \DB::raw('GROUP_CONCAT(employees_time_in_attendance.status ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_statuses'),
//                 \DB::raw('GROUP_CONCAT(employees_time_out_attendance.check_out_time ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_times'),
//                 \DB::raw('GROUP_CONCAT(employees_time_out_attendance.status ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_statuses')
//             )
//             ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
//             ->leftJoin('employees_time_out_attendance', function ($join) {
//                 $join->on('employees_time_in_attendance.employee_id', '=', 'employees_time_out_attendance.employee_id')
//                     ->on(\DB::raw('DATE(employees_time_in_attendance.check_in_time)'), '=', \DB::raw('DATE(employees_time_out_attendance.check_out_time)'));
//             })
//             ->whereIn('employees_time_in_attendance.status', ['On Leave', 'Official Travel'])
//             ->groupBy('employees_time_in_attendance.employee_id', 'employees.employee_lastname')
//             ->orderBy('check_in_times', 'asc')
//             ->orderBy('employees.employee_lastname', 'asc')

//             ->get();



//       $processedData = [];

//         // Initialize an array to hold the grouped data by name
//         $groupedByName = [];

//         foreach ($employeesWithLeaves as $employee) {
//             $checkInTimes = explode(', ', $employee->check_in_times);
//             $checkInStatuses = explode(', ', $employee->check_in_statuses);
//             $checkOutTimes = explode(', ', $employee->check_out_times);
//             $checkOutStatuses = explode(', ', $employee->check_out_statuses);

//             $groupedTimes = [];

//             foreach ($checkInTimes as $index => $checkInTime) {
//                 $date = \Carbon\Carbon::parse($checkInTime)->format('Y-m-d');
                
//                 if (isset($checkOutTimes[$index]) && \Carbon\Carbon::parse($checkOutTimes[$index])->format('Y-m-d') == $date) {
//                     if (isset($groupedTimes[$date])) {
//                         // Update check_out_time and check_out_status to the latest for that date
//                         $groupedTimes[$date]['check_out_time'] = $checkOutTimes[$index];
//                         $groupedTimes[$date]['check_out_status'] = $checkOutStatuses[$index];
//                     } else {
//                         // Add new entry with check_in_time, check_out_time, and statuses
//                         $groupedTimes[$date] = [
//                             'check_in_time' => $checkInTime,
//                             'check_in_status' => $checkInStatuses[$index],
//                             'check_out_time' => $checkOutTimes[$index],
//                             'check_out_status' => $checkOutStatuses[$index],
//                         ];
//                     }
//                 }
//             }

//             // Convert groupedTimes from associative array to indexed array for rendering
//             $groupedTimesArray = array_values($groupedTimes);

//             // Group data by employee name
//             $employeeName = $employee->employee_lastname; // You can use any other field for name as required
//             $employeeFirstname = $employee->employee->employee_firstname; // Retrieve the first name
//             if (!isset($groupedByName[$employeeName])) {
//                 $groupedByName[$employeeName] = [
//                     'employee_id' => $employee->employee_id,
//                     'employee_firstname' => $employeeFirstname,
//                     'times' => [],
//                 ];
//             }

//             $groupedByName[$employeeName]['times'] = array_merge($groupedByName[$employeeName]['times'], $groupedTimesArray);
//         }

//         $processedData = [];
//         $counter = 1; // Initialize a counter
//         $nameToNumber = []; // Initialize an array to keep track of assigned numbers

//         foreach ($groupedByName as $employeeName => $employeeData) {
//             // Create a unique key based on name and first name
//             $uniqueKey = $employeeName . ', ' . $employeeData['employee_firstname'];

//             // Check if this unique key already has a number assigned
//             if (!isset($nameToNumber[$uniqueKey])) {
//                 // Assign a new number if not already assigned
//                 $nameToNumber[$uniqueKey] = $counter;
//                 $counter++; // Increment the counter
//             }

//             $processedData[] = [
//                 'number' => $nameToNumber[$uniqueKey], // Use the assigned number
//                 'employee_name' => $employeeName,
//                 'employee_firstname' => $employeeData['employee_firstname'],
//                 'employee_id' => $employeeData['employee_id'],
//                 'times' => $employeeData['times'],
//             ];
//         }




// foreach ($processedData as $employeeData) {
//     $employeeName = $employeeData['employee_name'];
//     $employeeFirstname = $employeeData['employee_firstname'];
//     $employeeId = $employeeData['employee_id'];

//     foreach ($employeeData['times'] as $time) {
//         $checkInDate = Carbon::parse($time['check_in_time']);
//         $month = $checkInDate->format('F Y'); // e.g., "July 2024"

//         if (!isset($groupedByMonth[$month])) {
//             $groupedByMonth[$month] = [];
//         }

//         if (!isset($groupedByMonth[$month][$employeeName])) {
//             $groupedByMonth[$month][$employeeName] = [
//                 'employee_id' => $employeeId,
//                 'employee_firstname' => $employeeFirstname,
//                 'times' => [],
//             ];
//         }

//         $groupedByMonth[$month][$employeeName]['times'][] = $time;
//     }
// }

// Display the grouped data
        
        if($this->selectedMonth2) {
           $employeesWithLeaves = EmployeeAttendanceTimeIn::select(
                'employees_time_in_attendance.employee_id',
                'employees.employee_firstname',
                'employees.employee_lastname', 
                \DB::raw('GROUP_CONCAT(DATE(employees_time_in_attendance.check_in_time) ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_dates'),
                \DB::raw('GROUP_CONCAT(employees_time_in_attendance.check_in_time ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_times'),
                \DB::raw('GROUP_CONCAT(employees_time_in_attendance.status ORDER BY employees_time_in_attendance.check_in_time ASC SEPARATOR ", ") AS check_in_statuses'),
                \DB::raw('GROUP_CONCAT(employees_time_out_attendance.check_out_time ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_times'),
                \DB::raw('GROUP_CONCAT(employees_time_out_attendance.status ORDER BY employees_time_out_attendance.check_out_time ASC SEPARATOR ", ") AS check_out_statuses')
            )
            ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
            ->leftJoin('employees_time_out_attendance', function ($join) {
                $join->on('employees_time_in_attendance.employee_id', '=', 'employees_time_out_attendance.employee_id')
                    ->on(\DB::raw('DATE(employees_time_in_attendance.check_in_time)'), '=', \DB::raw('DATE(employees_time_out_attendance.check_out_time)'));
            })
            ->whereIn('employees_time_in_attendance.status', ['On Leave', 'Official Travel'])
            // Use selected month if set
            ->whereMonth('employees_time_in_attendance.check_in_time', '=', null)
            ->whereYear('employees_time_in_attendance.check_in_time', '=', null) // Optional: add the year filter if necessary
            ->groupBy('employees_time_in_attendance.employee_id', 'employees.employee_firstname', 'employees.employee_lastname') 
                        ->orderBy('check_in_times', 'asc')
            ->orderBy('employees.employee_lastname', 'asc')

            ->get();
            


            $processedData = [];

            // Group and process data
            foreach ($employeesWithLeaves as $employee) {
                $checkInTimes = explode(', ', $employee->check_in_times);
                $checkInStatuses = explode(', ', $employee->check_in_statuses);
                $checkOutTimes = explode(', ', $employee->check_out_times);
                $checkOutStatuses = explode(', ', $employee->check_out_statuses);

                $groupedTimes = [];
                foreach ($checkInTimes as $index => $checkInTime) {
                    $date = \Carbon\Carbon::parse($checkInTime)->format('Y-m-d');
                    if (isset($checkOutTimes[$index]) && \Carbon\Carbon::parse($checkOutTimes[$index])->format('Y-m-d') == $date) {
                        $groupedTimes[$date] = [
                            'check_in_time' => $checkInTime,
                            'check_in_status' => $checkInStatuses[$index],
                            'check_out_time' => $checkOutTimes[$index],
                            'check_out_status' => $checkOutStatuses[$index],
                        ];
                    }
                }

                $processedData[] = [
                    'employee_name' => $employee->employee_lastname .', '. $employee->employee_firstname,
                    'employee_id' => $employee->employee_id,
                    'times' => array_values($groupedTimes),
                ];
            }

            // Debug processed data before filtering
       

   
            foreach ($processedData as $employeeData) {
                foreach ($employeeData['times'] as $time) {
                    $checkInDate = \Carbon\Carbon::parse($time['check_in_time']);
                    $month = $checkInDate->format('F Y');
                    $monthNumber = $checkInDate->month;

                    if ($this->selectedMonth2 && $monthNumber != $this->selectedMonth2) {
                        continue; // Skip if the month doesn't match
                    }

                    if (!isset($this->groupedByMonth[$month])) {
                        $this->groupedByMonth[$month] = [];
                    }

                    $this->groupedByMonth[$month][$employeeData['employee_name']] = [
                        'employee_id' => $employeeData['employee_id'],
                        'times' => $employeeData['times'],
                    ];
                }
            }
        } else {
          $processedData = null;
          $this->selectedMonth2 = null;
          $this->groupedByMonth = [];
        }
            // Debug grouped data by month
          



// Output or further process $processedData as needed

       
        return view('livewire.admin.display-datafor-payroll', [
            'overallTotalHours' => $overallTotalHours,
            'overallTotalLateHours' => $overallTotalLateHours,
            'overallTotalHoursSum' => $overallTotalHoursSum,
            'overallTotalUndertime' => $overallTotalUndertime,
            'totalHoursTobeRendered' => $totalHoursTobeRendered,
            'attendanceData' =>$attendanceData,
            'attendanceTimeIn' => $attendanceTimeIn,
            'attendanceTimeOut' => $attendanceTimeOut,
            'schools' => $schools,
            'departments' => $departments,
            'schoolToShow' => $this->schoolToShow,
            'departmentToShow' => $this->departmentToShow,
            'selectedEmployeeToShow' => $this->selectedEmployeeToShow,
            'employees' => $employees, // Ensure employees variable is defined if needed
            'selectedAttendanceByDate' => $this->selectedAttendanceByDate,
            'departmentDisplayWorkingHour' => $departmentDisplayWorkingHour,
            'holidays' => $holidays,
            'processedData' => $processedData,
            'months' => $months,
            'currentMonth' => $this->selectedMonth,
            'groupedByMonth' => $this->groupedByMonth,
        ]);
    }



    public function generatePDF()
    {
        $savePath = storage_path('/app/generatedPDF'); // Default save path (storage/app/)
        // $savePath = 'C:/Users/YourUsername/Downloads/'; // Windows example
        $departments = Department::where('id', $this->selectedDepartment4)->get();
        $department = Department::find($this->selectedDepartment4);

            $currentYear = $this->selectedYear;
            $currentMonth = Carbon::now()->month;
        try {



           // Determine the filename dynamically with date included if both startDate and endDate are selected
            if ($this->startDate && $this->endDate) {
               
                // Combine numeric day with current month and year to create a valid date
                $fullStartDate = "{$currentYear}-{$currentMonth}-{$this->startDate}";
                $fullEndDate = "{$currentYear}-{$currentMonth}-{$this->endDate}";

                // Format using Carbon
                $selectedStartDate = Carbon::parse($fullStartDate)->format('jS F Y');
                $selectedEndDate = Carbon::parse($fullEndDate)->format('jS F Y');

                $dateRange = $selectedStartDate . ' to ' . $selectedEndDate;
            } else {
                $dateRange = Carbon::createFromFormat('m', $this->selectedMonth)->format('F') . ', ' . $this->selectedYear;
            }

            // Construct the filename with the date range if available
            

            // Check if the department was found
            if ($department) {
                // Access a specific column, e.g., 'name'
                $departmentName = $department->department_abbreviation;

                // Construct the filename with the department name and date range
                $filename = $departmentName . ' - ' . $dateRange . '.pdf';
            } else {
                // Handle the case where the department was not found
                $filename = 'Unknown Department - ' . $dateRange . '.pdf';
            }


            // Base query for EmployeeAttendanceTimeIn with left join to EmployeeAttendanceTimeOut
            $queryTimeIn = EmployeeAttendanceTimeIn::query()
                ->with(['employee.school', 'employee.department']);
            $queryTimeOut = EmployeeAttendanceTimeOut::query()
                ->with(['employee.school', 'employee.department']);
                
            // Apply selected school filter
            if ($this->selectedSchool) {
                $queryTimeIn->whereHas('employee', function (Builder $query) {
                    $query->where('school_id', $this->selectedSchool);
                });
                $queryTimeOut->whereHas('employee', function (Builder $query) {
                    $query->where('school_id', $this->selectedSchool);
                });
                $this->schoolToShow = School::find($this->selectedSchool);
            } else {
                $this->schoolToShow = null;
            }

            // Apply selected department filter
            if ($this->selectedDepartment4) {
                $queryTimeIn->whereHas('employee', function (Builder $query) {
                    $query->where('department_id', $this->selectedDepartment4);
                });
                $queryTimeOut->whereHas('employee', function (Builder $query) {
                    $query->where('department_id', $this->selectedDepartment4);
                });
                $this->departmentToShow = Department::find($this->selectedDepartment4);
                $employees = Employee::where('department_id', $this->selectedDepartment4)->get();
            } else {
                $this->departmentToShow = null;
                $employees = Employee::all();
            }

            // Apply selected employee filter
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
                            
                $selectedAttendanceByDate = $queryTimeIn->get();// Fetch data and assign to selectedAttendanceByDate
                
                $this->selectedAttendanceByDate = $selectedAttendanceByDate;   
            }
            
            // $attendanceTimeIn = $queryTimeIn->orderBy($this->sortField, $this->sortDirection)
            //     ->paginate(50);
            // $attendanceTimeOut = $queryTimeOut->orderBy($this->sortField, $this->sortDirection)
            //     ->paginate(50);


            // $attendanceTimeIn = $queryTimeIn->orderBy('employee_id', 'asc')
            //     ->paginate(10);

            // $attendanceTimeOut = $queryTimeOut->orderBy('employee_id', 'asc')
            //     ->paginate(10);

            // $attendanceTimeIn = $queryTimeIn->orderBy('employee_id', 'asc')->get();
            // $attendanceTimeOut = $queryTimeOut->orderBy('employee_id', 'asc')->get();   
            // $attendanceTimeIn = $queryTimeIn->orderBy('employee_id', 'asc')
            //     ->orderBy('check_in_time', 'asc')
            //     ->paginate(1000);

            // $attendanceTimeOut = $queryTimeOut->orderBy('employee_id', 'asc')
            //     ->orderBy('check_out_time', 'asc')
            //     ->paginate(1000);

            // $attendanceTimeIn = $queryTimeIn->where('status', '!=', 'Holiday')
            //     ->orderBy('employee_id', 'asc')
            //     ->orderBy('check_in_time', 'asc')
            //     ->paginate(1000);

            // $attendanceTimeOut = $queryTimeOut->where('status', '!=', 'Holiday')
            //     ->orderBy('employee_id', 'asc')
            //     ->orderBy('check_out_time', 'asc')
            //     ->paginate(1000);

            $attendanceTimeIn = $queryTimeIn
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_in_time', $currentMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year        
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

            $attendanceTimeOut = $queryTimeOut
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_out_time', $currentMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();


        $attendanceData = [];
        $overallTotalHours = 0;
        $overallTotalLateHours = 0;
        $overallTotalUndertime = 0;
        $totalHoursTobeRendered = 0;
        $overallTotalHoursSum = 0;

        foreach ($attendanceTimeIn as $attendance) {
            // Initialize variables for each record
            $hoursWorkedAM = 0;
            $hoursWorkedPM = 0;
            $lateDurationAM = 0;
            $lateDurationPM = 0;
            $undertimeAM = 0;
            $undertimePM = 0;
            $totalHoursLate = 0;
            $totalUndertimeHours = 0;
            $totalLateandUndertime = 0;
            $latePM = 0;
            $lateAM = 0;
            $undertimeAMTotal = 0;
            $undertimePMTotal = 0;
            $totalundertime = 0;
            $totalhoursNeed = 0;
            $totalHoursNeedperDay = 0;

            $totalHoursByDay = [];
            $overallTotalHoursSumm = 0;
            
            $departmentId = $attendance->employee->department_id;

            $workingHoursByDay = DepartmentWorkingHour::select(
                    'day_of_week',
                    'morning_start_time',
                    'morning_end_time',
                    'afternoon_start_time',
                    'afternoon_end_time'
                )
                ->where('department_id', $departmentId)
                ->where('day_of_week', '!=', 0)
                ->get()
                ->groupBy('day_of_week');

            

            foreach ($workingHoursByDay as $dayOfWeek => $workingHours) {
                $totalHours = 0;

                foreach ($workingHours as $workingHour) {
                    if ($workingHour->morning_start_time && $workingHour->morning_end_time) {
                        $morningStart = Carbon::parse($workingHour->morning_start_time);
                        $morningEnd = Carbon::parse($workingHour->morning_end_time);
                        $totalHours += $morningStart->diffInHours($morningEnd);
                        
                    }

                    if ($workingHour->afternoon_start_time && $workingHour->afternoon_end_time) {
                        $afternoonStart = Carbon::parse($workingHour->afternoon_start_time);
                        $afternoonEnd = Carbon::parse($workingHour->afternoon_end_time);
                        $totalHours += $afternoonStart->diffInHours($afternoonEnd);
                    }
                }

                $totalHoursByDay[$dayOfWeek] = $totalHours;
                $overallTotalHoursSumm += $totalHours;
            }

            // foreach ($totalHoursByDay as $dayOfWeek => $totalHours) {
            //     echo "Day of Week: $dayOfWeek\n";
            //     echo "Total Working Hours: $totalHours hours\n";
            //     echo "------------------------\n";
            // }
            // echo "Overall Total Working Hours: $overallTotalHours hours\n";

            $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
            // Extract date and time from check-in


            // Find corresponding check-out time
            $checkOut = $attendanceTimeOut->where('employee_id', $attendance->employee_id)
                                            ->where('check_out_time', '>=', $attendance->check_in_time)
                                            ->first();

            if ($checkOut) {
                $checkOutDateTime = new DateTime($checkOut->check_out_time);


                // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                //                                                 ->where('day_of_week', '!=', 6)
                //                                                 ->first();

                    $checkInDateTime = new DateTime($attendance->check_in_time);
                    $checkInDate = $checkInDateTime->format('Y-m-d');
                    $checkInTime = $checkInDateTime->format('H:i:s'); // Extracted time part
                    $dayOfWeek = $checkInDateTime->format('w');

                // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                //                                                 ->where('day_of_week', '!=', 0)
                //                                                 ->first();

                $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                                                ->where('day_of_week', $dayOfWeek)
                                                ->where('day_of_week', '!=', 0)
                                                ->first();

                
                


                
                if ($departmentWorkingHour) 
                {   

                
                    $mS = $departmentWorkingHour->morning_start_time;
                    $morningStartTime = clone $checkInDateTime;
                    $morningStartTime->setTime(
                        (int) date('H', strtotime($mS)),
                        (int) date('i', strtotime($mS)),
                        (int) date('s', strtotime($mS))
                    );

                    $morStart = $morningStartTime->setTime(
                        (int) date('H', strtotime($mS)),
                        (int) date('i', strtotime($mS)),
                        (int) date('s', strtotime($mS))
                    );

                    $mE = $departmentWorkingHour->morning_end_time;
                    $morningEndTime = clone $checkInDateTime;
                    $morningEndTime->setTime(
                        (int) date('H', strtotime($mE)),
                        (int) date('i', strtotime($mE)),
                        (int) date('s', strtotime($mE))
                    );

                    $aS = $departmentWorkingHour->afternoon_start_time;
                    $afternoonStartTime = clone $checkInDateTime;
                    $afternoonStartTime->setTime(
                        (int) date('H', strtotime($aS)),
                        (int) date('i', strtotime($aS)),
                        (int) date('s', strtotime($aS))
                    );

                    
                    $aE = $departmentWorkingHour->afternoon_end_time;
                        $afternoonEndTime = clone $checkInDateTime;
                        $afternoonEndTime->setTime(
                            (int) date('H', strtotime($aE)),
                            (int) date('i', strtotime($aE)),
                            (int) date('s', strtotime($aE))
                        );
                    
                    $morningStartTimew = $departmentWorkingHour->morning_start_time;
                    $morningEndTimew = $departmentWorkingHour->morning_end_time;
                    $afternoonStartTimew = $departmentWorkingHour->afternoon_start_time;
                    $afternoonEndTimew = $departmentWorkingHour->afternoon_end_time;

                        // Convert times to Carbon instances
                    $morningStartw = new DateTime($morningStartTimew);
                    $morningEndw = new DateTime($morningEndTimew);
                    $afternoonStartw = new DateTime($afternoonStartTimew);
                    $afternoonEndw = new DateTime($afternoonEndTimew);

                    // Calculate the duration in minutes for morning and afternoon
                    $morningInterval = $morningStartw->diff($morningEndw);
                    $morningDurationInMinutes = ($morningInterval->h * 60) + $morningInterval->i;
                    $afternoonInterval = $afternoonStartw->diff($afternoonEndw);
                    $afternoonDurationInMinutes = ($afternoonInterval->h * 60) + $afternoonInterval->i;

                    // Convert minutes to hours
                    $morningDuration = $morningDurationInMinutes / 60;
                    $afternoonDuration = $afternoonDurationInMinutes / 60;
                    // Calculate total hours needed
                    $totalHoursNeed = $morningDuration + $afternoonDuration;
                    $totalHoursTobeRendered = $totalHoursNeed;
                    $totalHoursNeedperDay = $totalHoursNeed;
                    if ($this->startDate && $this->endDate) {
                        $employeeId = $attendance->employee_id; // Assuming you have this from $attendance

                        // Determine if the start date and end date are the same
                        $isSameDate = $this->startDate === $this->endDate; // Adjust if necessary for your date format
                        // $startDate = Carbon::parse($this->startDate)->startOfDay(); // Start of the selected start date
                        // $endDate = Carbon::parse($this->endDate)->endOfDay(); // End of the selected end date
                         $startDate = Carbon::parse("{$currentYear}-{$currentMonth}-{$this->startDate}")->startOfDay();
                        // $endDate = Carbon::parse($this->endDate)->endOfDay(); // End of the selected end date
                        $endDate = Carbon::parse("{$currentYear}-{$currentMonth}-{$this->endDate}")->startOfDay();

                        if ($isSameDate) {
                            // If the start date and end date are the same, only consider that specific day
                            $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                                ->where('employee_id', $employeeId)
                                ->whereDay('check_in_time', $this->startDate)
                                ->first();
                        } else {
                            // If the start date and end date are different, consider the range
                            // $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                            //     ->where('employee_id', $employeeId)
                            //     ->whereBetween('check_in_time', [$startDate, $endDate])
                            //     ->first();
                            $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(employees_time_in_attendance.check_in_time)) as unique_check_in_days'))
                                        ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
                                        ->join('working_hour', function($join) {
                                            $join->on('employees.department_id', '=', 'working_hour.department_id');
                                        })
                                        ->where('employees_time_in_attendance.employee_id', $employeeId)
                                        ->whereNotIn('employees_time_in_attendance.status', ['Absent', 'AWOL', 'On Leave'])
                                        ->whereNotIn('working_hour.day_of_week', [0, 6])
                                        ->whereBetween('check_in_time', [$startDate, $endDate]) // Exclude Saturday (6) and Sunday (7)
                                        ->first();
                                    

                        }

                        // Get the unique check-in days count
                        $uniqueCheckInDays = (int) $checkInCount->unique_check_in_days;
                        
                        // Calculate total hours to be rendered
                        $totalHoursTobeRendered = $totalHoursNeed * $uniqueCheckInDays;
                    } else {
                        
                        $employeeId = $attendance->employee_id; // Assuming you have this from $attendance
                        

                        $noww = new DateTime('now', new DateTimeZone('Asia/Taipei'));
                        $currentDatee = $noww->format('Y-m-d') . ' 00:00:00';

                        $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                            ->where('employee_id', $employeeId)
                            ->where('check_in_time', '<>', $currentDatee)
                            ->whereNotIn('status', ['Absent', 'AWOL', 'On Leave'])
                            ->first();

                        $uniqueCheckInDays = (int) $checkInCount->unique_check_in_days;
                        $totalHoursTobeRendered = $totalHoursNeed * $uniqueCheckInDays;

                   
                    }
                    
                    $gracePeriodFirst = GracePeriod::first();
                    if($gracePeriodFirst){
                        $gracePeriodValue = (float) $gracePeriodFirst->grace_period;
                        // AM Shift Calculation  for 15 mins interval of declaring late
                        if ($checkInDateTime < $morningEndTime) {
                            $effectiveCheckInTime = max($checkInDateTime, $morningStartTime);
                            $effectiveCheckOutTime = min($checkOutDateTime, $morningEndTime);
                            if ($effectiveCheckInTime < $effectiveCheckOutTime) {
                                $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60);
                                $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                
                                // Calculate late duration for AM
                                

                                // Check if there's only one check-in and check-out in the same day
                                // if ($checkInDateTime->format('Y-m-d') == $checkOutDateTime->format('Y-m-d')) {
                                //     $hoursWorkedAM = 0;
                                // }

                                $dateKey1 = $checkInDateTime->format('Y-m-d');
                                $dateKey2 = $checkOutDateTime->format('Y-m-d');

                                // Fetch counts of check-ins and check-outs for the specified dates
                                $checkOuttCount = EmployeeAttendanceTimeOut::select(DB::raw('DATE(check_out_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_out_time)'))
                                    ->pluck('count', 'date');

                                $checkInnCount = EmployeeAttendanceTimeIn::select(DB::raw('DATE(check_in_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_in_time)'))
                                    ->pluck('count', 'date');

                                // Fetch the first check-in and check-out times for the specified date
                                $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->first();

                                $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->first();

                                $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                // Calculate PM hours if applicable
                                if ($checkInnCount->get($dateKey1, 0) == 1 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                    
                                    if ($firstCheckIn && $firstCheckOut) {
                                        $checkInTime = new DateTime($firstCheckIn->check_in_time);
                                        $checkOutTime = new DateTime($firstCheckOut->check_out_time);

                                        // Ensure check-in is PM and check-out is also PM
                                        if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'pm') {
                                            $amEndTime = new DateTime($dateKey1 . ' 14:00:00');
                                            if ($checkOutTime < $amEndTime) {
                                            // Calculate the interval between the check-out time and 1:00 PM
                                            //so naa cutoff ang checkout sa buntag 1 pwedi i set $amEndTime
                                            // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                            
                                            $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);

                                            }else{
                                                $hoursWorkedAM = 0;
                                            }
                                        }
                                        else {
                                            // $hoursWorkedAM = 0;
                                        }
                                    } else {
                                        $hoursWorkedAM = 0;
                                    }
                                } else {
                                     
                                        if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                            if ($firstCheckIn && $firstCheckOut && $secondCheckIn && $secondCheckOut) {
                                                
                                                $checkInTime = new DateTime($firstCheckIn->check_in_time);
                                                $checkOutTime = new DateTime($firstCheckOut->check_out_time);

                                                $checkInTime2 = Carbon::parse($secondCheckIn->check_in_time);
                                                $checkOutTime2 = Carbon::parse($secondCheckOut->check_out_time);

                                                if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'am' && $checkInTime2->format('a') === 'pm' && $checkOutTime2->format('a') === 'am') { 
                                                    $hoursWorkedAM = 0;
                                                }
                                            }
                                        }
                                    

                                    // if ($checkInnCount->get($dateKey1, 0) == 1 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                    //     $hoursWorkedAM = 0;
                                    // }

                                }


                                if ($checkInDateTime > $morningStartTime) {
                                    // Define the latest allowed check-in time with a 15-minute grace period
                                    // $latestAllowedCheckInAM = clone $morningStartTime;
                                    // $latestAllowedCheckInAM->add(new DateInterval('PT15M'));
                                    // Rounds to nearest integer

                                    
                                    $gracePeriodMinutes = $gracePeriodValue * 60;
                                    $gracePeriodMinutes = round($gracePeriodMinutes);
                                    $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                    
                                    // Clone the original time and add the interval
                                    $latestAllowedCheckInAM = clone $morningStartTime;
                                    $latestAllowedCheckInAM->add(new DateInterval($intervalSpec));

                                    // Check if the check-in time is beyond the 15-minute grace period
                                    if ($checkInDateTime > $latestAllowedCheckInAM ) {

                                        // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        // $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                        // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        // $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                        // Calculate the late interval starting from the grace period end

                                        // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        // // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60);
                                        // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                        

                                        $lateIntervalAM = $checkInDateTime->diff($latestAllowedCheckInAM);
                                        $lateDurationAM = ($lateIntervalAM->h * 60) + $lateIntervalAM->i + ($lateIntervalAM->s / 60);
                                        
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $lateAM = $lateIntervalAM->h + ($lateIntervalAM->i / 60) + ($lateIntervalAM->s / 3600);
                                        
                                        


                                    } else {
                                        $lateIntervalAM = $checkInDateTime->diff($latestAllowedCheckInAM);
                                        $lateDurationAM = ($lateIntervalAM->h * 60) + $lateIntervalAM->i + ($lateIntervalAM->s / 60);
                                        
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $lateAM = $lateIntervalAM->h + ($lateIntervalAM->i / 60) + ($lateIntervalAM->s / 3600);
                                        
                                        // Calculate hours worked in the AM
                                        $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);

                                        // Calculate the difference in minutes (or hours) from $checkInDateTime to $morningStartTime
                                        $intervalToMorningStart = $checkInDateTime->diff($morningStartTime);
                                        $dataInMinutes = $intervalToMorningStart->h * 60 + $intervalToMorningStart->i + ($intervalToMorningStart->s / 60);
                                        $dataInHours = $dataInMinutes / 60;

                                        $hoursWorkedAM = $hoursWorkedAM + $dataInHours;
                                        
                                        $lateDurationAM = 0;
                                        $lateAM = 0;
                                        
                                    }
                                        
                                    // if($checkInDateTime < $latestAllowedCheckInAM)
                                    // {
                                        
                                    //     // $intervalAM = $morningStartTime->diff($effectiveCheckOutTime);
                                        
                                    //     // // Convert intervals to total minutes
                                    //     // $intervalMinutesAM = ($intervalAM->h * 60) + $intervalAM->i + ($intervalAM->s / 60);

                                    //     // // Calculate hours worked in AM
                                    //     // $hoursWorkedAM = $intervalMinutesAM / 60; // Convert total minutes to hours
                                    //     // $hoursWorkedAM += 0.25;
                                    //     // $lateDurationAM = 0;
                                    //     // $lateAM = 0;
                                        
                                    // }
                                } else {
                                    // If check-in is on time or early, set late duration to 0
                                        $lateDurationAM = 0;
                                        $lateAM = 0;
                                        
                                }

                                if ($lateDurationAM > 0 ) {
                                    // $hoursWorkedAM += 0.25; // Subtract 0.25 hours (15 minutes) if late
                                    
                                    $hoursWorkedAM += $gracePeriodValue;
                                    
                                }
                                // if ($lateDurationAM > 0 ) {
                                //     // $hoursWorkedAM += 0.25; // Subtract 0.25 hours (15 minutes) if late
                                    
                                //     $hoursWorkedAM += $gracePeriodValue;
                                    
                                // }

                                if ($lateDurationAM > 0 && $hoursWorkedAM == $gracePeriodValue) {
                                    $hoursWorkedAM -= $gracePeriodValue;
                                    $lateDurationAM = 0;
                                    $lateAM = 0; // Subtract 0.25 hours (15 minutes) if late
                                }

                                
                                // if($lateDurationAM > 0 && $hoursWorkedAM > $gracePeriodValue){
                                //          $hoursWorkedAM += $gracePeriodValue;
                                // }

                                // if ($lateDurationAM > 0 && $hoursWorkedAM == 0.25) {
                                //     $hoursWorkedAM -= 0.25;
                                //     $lateDurationAM = 0;
                                //     $lateAM = 0; // Subtract 0.25 hours (15 minutes) if late
                                // }   
                                
                                // if ($checkInDateTime <= $latestAllowedCheckInAM) {
                                //     // Calculate the total hours worked considering the effective check-in time and the morning end time
                                //     $effectiveCheckInTime = max($checkInDateTime, $morningStartTime);
                                //     $effectiveCheckOutTime = min($checkOutDateTime, $morningEndTime);
                                //     $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                //     $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                // }


                            }

                                $scheduledDiff = $morningStartTime->diff($morningEndTime);
                                $scheduledAMMinutes = ($scheduledDiff->h * 60) + $scheduledDiff->i + ($scheduledDiff->s / 60);

                                // Calculate actual worked time up to the morning end time including seconds
                                if ($effectiveCheckOutTime < $morningEndTime) {
                                    $actualDiff = $effectiveCheckOutTime->diff($morningStartTime);
                                } else {
                                    $actualDiff = $morningEndTime->diff($morningStartTime);
                                }
                                $actualMinutesUpToEnd = ($actualDiff->h * 60) + $actualDiff->i + ($actualDiff->s / 60);
                                    $undertimeAMTotal = max(0, $scheduledAMMinutes - $actualMinutesUpToEnd);
                                // Calculate undertime in minutes
                                $undertimeAM = max(0, $scheduledAMMinutes - $actualMinutesUpToEnd);
                    
                        }   
                    

                        // PM Shift Calculation
                        if ($checkInDateTime < $afternoonEndTime && $checkOutDateTime > $afternoonStartTime) {
                            $effectiveCheckInTime = max($checkInDateTime, $afternoonStartTime);
                            $effectiveCheckOutTime = min($checkOutDateTime, $afternoonEndTime);
                            if ($effectiveCheckInTime < $effectiveCheckOutTime) {
                                $intervalPM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                // $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                // Calculate late duration for PM
                                // $latestAllowedCheckInPM = clone $afternoonStartTime;
                                // $latestAllowedCheckInPM->add(new DateInterval('PT15M'));

                                    $gracePeriodMinutes = $gracePeriodValue * 60;
                                    $gracePeriodMinutes = round($gracePeriodMinutes);
                                    $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                    
                                    // Clone the original time and add the interval
                                    $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    $latestAllowedCheckInPM->add(new DateInterval($intervalSpec));

                                // Check if there's only one check-in and check-out in the same day
                                // if ($checkInDateTime->format('Y-m-d') == $checkOutDateTime->format('Y-m-d')) {
                                //     $hoursWorkedPM = 0;
                                // }

                                // Convert the check-in and check-out date to the format 'Y-m-d'
                                $dateKey1 = $checkInDateTime->format('Y-m-d');
                                $dateKey2 = $checkOutDateTime->format('Y-m-d');

                                // Fetch counts of check-ins and check-outs for the specified dates
                                $checkOuttCount = EmployeeAttendanceTimeOut::select(DB::raw('DATE(check_out_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_out_time)'))
                                    ->pluck('count', 'date');

                                $checkInnCount = EmployeeAttendanceTimeIn::select(DB::raw('DATE(check_in_time) as date, COUNT(*) as count'))
                                    ->where('employee_id', $employeeId)
                                    ->groupBy(DB::raw('DATE(check_in_time)'))
                                    ->pluck('count', 'date');

                                // Fetch the first check-in and check-out times for the specified date
                                $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->first();

                                $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->first();

                                $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                    ->whereDate('check_in_time', $dateKey1)
                                    ->orderBy('check_in_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                    ->whereDate('check_out_time', $dateKey2)
                                    ->orderBy('check_out_time', 'asc')
                                    ->skip(1)
                                    ->first();

                                // Calculate PM hours if applicable
                                   
                                if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                    if ($secondCheckIn && $secondCheckOut) {
                                        // Convert check-in and check-out times to Carbon instances
                                        $checkInTime = Carbon::parse($secondCheckIn->check_in_time);
                                        $checkOutTime = Carbon::parse($secondCheckOut->check_out_time);

                                        // Ensure check-in is PM and check-out is also PM
                                        if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'pm') {
                                            $timeStartTimeSecond = Carbon::parse($afternoonStartTime)->format('H:i:s');
                                            $secondTime = Carbon::parse($secondCheckIn->check_in_time)->format('H:i:s');
                                            
                                            //dd($afternoonStartTime > $secondTime);
                                            // if($afternoonStartTime > $secondTime){
                                            //     $hoursWorkedPM = 0;
                                            // } else {
                                               $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                                // if($afternoonStartTime > $secondTime){
                                            //     $hoursWorkedPM = 0;
                                            // } else {
                                            // }
                                        } else {
                                            $hoursWorkedPM = 0;
                                        }
                                    } else {
                                        $hoursWorkedPM = 0;
                                    }
                                } else {
                                    // Set to 0 if counts are not both 2
                                        
                                    if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                        $hoursWorkedPM = 0;
                                    } 
                                    else 
                                    {
                                        if ($firstCheckIn && $firstCheckOut) {
                                            // Convert check-in and check-out times to Carbon instances
                                            $checkInTime = Carbon::parse($firstCheckIn->check_in_time);
                                            $checkOutTime = Carbon::parse($firstCheckOut->check_out_time);

                                            // Ensure check-in is PM and check-out is also PM
                                            if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'pm') {
                                                $hoursWorkedPM = 0;
                                            } else if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'am') {
                                                $hoursWorkedPM = 0;
                                            }else {
                                                $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);
                                            }
                                        }
                                    }
   
                                }



                                
                                if ($checkInDateTime > $afternoonStartTime) {

                                    // $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    // $latestAllowedCheckInPM->add(new DateInterval('PT15M'));

                                    $gracePeriodMinutes = $gracePeriodValue * 60;
                                    $gracePeriodMinutes = round($gracePeriodMinutes);
                                    $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                    
                                    // Clone the original time and add the interval
                                    $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    $latestAllowedCheckInPM->add(new DateInterval($intervalSpec));

                                    // $lateIntervalPM = $checkInDateTime->diff($afternoonStartTime);
                                    // $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                    // $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);

                                    // Check if the check-in time is beyond the 15-minute grace period
                                    if ($checkInDateTime > $latestAllowedCheckInPM ) {
                                        // Calculate the late interval starting from the grace period end
                                        $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                        $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                        

                                    } else {
                                        // If within the grace period, set late duration to 0
                                        // $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                        // $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        // // Calculate the late duration in hours, minutes, and seconds
                                        
                                        // $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                        // $lateDurationPM = 0;
                                        // $latePM = 0;

                                        $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                        $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        
                                        // Calculate the late duration in hours, minutes, and seconds
                                        
                                        $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                        
                                        // Calculate hours worked in the PM
                                        $intervalPM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                        $hoursWork = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                        // Calculate the difference in minutes (or hours) from $checkInDateTime to $morningStartTime
                                        $intervalToAfternoonStart = $checkInDateTime->diff($afternoonStartTime);
                                        $dataInMinutes = $intervalToAfternoonStart->h * 60 + $intervalToAfternoonStart->i + ($intervalToAfternoonStart->s / 60);
                                        $dataInHours = $dataInMinutes / 60;

                                        if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                            if ($secondCheckIn && $secondCheckOut) {
                                                // Convert check-in and check-out times to Carbon instances
                                                $checkInTime = Carbon::parse($secondCheckIn->check_in_time);
                                                $checkOutTime = Carbon::parse($secondCheckOut->check_out_time);

                                                // Ensure check-in is PM and check-out is also PM
                                                if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'pm') {
                                                    $timeStartTimeSecond = Carbon::parse($afternoonStartTime)->format('H:i:s');
                                                    $secondTime = Carbon::parse($secondCheckIn->check_in_time)->format('H:i:s');
                                                    
                                                    //dd($afternoonStartTime > $secondTime);
                                                    // if($afternoonStartTime > $secondTime){
                                                    //     $hoursWorkedPM = 0;
                                                    // } else {
                                                  $hoursWorkedPM = $hoursWorkedPM + $dataInHours;

                                                        // if($afternoonStartTime > $secondTime){
                                                    //     $hoursWorkedPM = 0;
                                                    // } else {
                                                    // }
                                                } else {
                                                    $hoursWorkedPM = 0;
                                                }
                                            } else {
                                                $hoursWorkedPM = 0;
                                            }
                                        } else {
                                            // Set to 0 if counts are not both 2
                                            
                                            if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                                $hoursWorkedPM = 0;
                                            }
                                        }
                                                

                                                                              
                                        
                                         
                                        $lateDurationPM = 0;
                                        $latePM = 0;
                                        
                                        
                                    }

                                    // if($checkInDateTime <= $latestAllowedCheckInPM)
                                    // {
                                        
                                    //     $intervalPM = $afternoonStartTime->diff($effectiveCheckOutTime);
                                        
                                    //     // Convert intervals to total minutes
                                    //     $intervalMinutesPM = ($intervalPM->h * 60) + $intervalPM->i + ($intervalPM->s / 60);

                                    //     // Calculate hours worked in AM
                                    //     $hoursWorkedPM= $intervalMinutesPM / 60; // Convert total minutes to hours
                                        

                                    // }

                                } else {
                                    // If check-in is on time or early, set late duration to 0
                                        $lateDurationPM = 0;
                                        $latePM = 0;
                                        
                                }

                                if ($lateDurationPM > 0) {
                                    $hoursWorkedPM += $gracePeriodValue; // Subtract 0.25 hours (15 minutes) if late
    
                                }

                                if ($lateDurationPM > 0 && $hoursWorkedPM == $gracePeriodValue) {
                                    $hoursWorkedPM -= $gracePeriodValue;
                                    $lateDurationPM = 0;
                                    $latePM = 0; // Subtract 0.25 hours (15 minutes) if late
                                }

                                
                                

                                //  if ($lateDurationPM > 0) {
                                //     $hoursWorkedPM += 0.25; // Subtract 0.25 hours (15 minutes) if late
    
                                // }
                                
                            }

                            

                            $scheduledPMDiff = $afternoonStartTime->diff($afternoonEndTime);
                            $scheduledPMMinutes = ($scheduledPMDiff->h * 60) + $scheduledPMDiff->i + ($scheduledPMDiff->s / 60);

                            // Calculate actual worked time up to the afternoon end time including seconds
                            if ($effectiveCheckOutTime < $afternoonEndTime) {
                                $actualPMDiff = $effectiveCheckOutTime->diff($afternoonStartTime);
                            } else {
                                $actualPMDiff = $afternoonEndTime->diff($afternoonStartTime);
                            }
                            $actualMinutesUpToEndPM = ($actualPMDiff->h * 60) + $actualPMDiff->i + ($actualPMDiff->s / 60);
                            $undertimePMTotal = max(0, $scheduledPMMinutes - $actualMinutesUpToEndPM);
                            // Calculate undertime in minutes
                            $undertimePM = max(0, $scheduledPMMinutes - $actualMinutesUpToEndPM);

                        }
                    }



                    // Calculate total hours worked
                    $totalHoursWorked = $hoursWorkedAM + $hoursWorkedPM;
                    
                    $totalHoursLate = $lateDurationAM + $lateDurationPM;
                    $totalUndertimeHours = $undertimeAM + $undertimePM;
                    $overallTotalHoursLate = $lateAM + $latePM;
                    $totalundertime = $undertimeAMTotal + $undertimePMTotal;

                    // $totalhoursNeed = $morningDuration + $afternoonDuration;
    
                    // Determine remark based on lateness
                    $remark = ($lateDurationAM > 0 || $lateDurationPM > 0) ? 'Late' : 'Present';

                    $modifyStatus = $attendance->status;

          

                    // Prepare the key for $attendanceData
                    $key = $attendance->employee_id . '-' . $checkInDate;

                     $employee_idd = $attendance->employee->employee_id;
                    $employee_id = $attendance->employee_id;
                    $employeeLastname = $attendance->employee->employee_lastname;
                    $employeeFirstname = $attendance->employee->employee_firstname;
                    $employeeMiddlename = $attendance->employee->employee_middlename;
                    $checkInTimer = $attendance->check_in_time;

                    
                    // Check if this entry already exists in $attendanceData
                    if (isset($attendanceData[$key])) {
                        // Update existing entry
                        
                        $attendanceData[$key]->hours_perDay = $totalHoursNeedperDay;
                        $attendanceData[$key]->hours_workedAM += $hoursWorkedAM;
                        $attendanceData[$key]->hours_workedPM += $hoursWorkedPM;
                        $attendanceData[$key]->total_hours_worked += $totalHoursWorked;
                        $attendanceData[$key]->total_hours_late += $totalHoursLate;
                        $attendanceData[$key]->late_duration += $lateDurationAM;
                        $attendanceData[$key]->late_durationPM += $lateDurationPM;
                        $attendanceData[$key]->undertimeAM += $undertimeAM;
                        $attendanceData[$key]->undertimePM += $undertimePM;
                        $attendanceData[$key]->total_late += $totalHoursLate;
                        $attendanceData[$key]->remarks = $remark;
                        $attendanceData[$key]->modify_status = $modifyStatus;
                        $attendanceData[$key]->employee_idd = $employee_idd;
                        $attendanceData[$key]->employee_id = $employee_id;
                        $attendanceData[$key]->employee_lastname = $employeeLastname;
                        $attendanceData[$key]->employee_firstname = $employeeFirstname;
                        $attendanceData[$key]->employee_middlename = $employeeMiddlename;
                        $attendanceData[$key]->hours_late_overall += $overallTotalHoursLate;
                        $attendanceData[$key]->hours_undertime_overall += $totalundertime;
                        $attendanceData[$key]->check_in_time = $checkInTimer;
   


                        // dd($attendanceData[$key]->undertimeAM += $undertimeAM);
                    } else {
                        // Create new entry
                        $attendanceData[$key] = (object) [
                            'hours_perDay' => $totalHoursNeedperDay,
                            'employee_id' => $attendance->employee_id,
                            'worked_date' => $checkInDate,
                            'hours_workedAM' => $hoursWorkedAM,
                            'hours_workedPM' => $hoursWorkedPM,
                            'total_hours_worked' => $totalHoursWorked,
                            'total_hours_late' => $totalHoursLate,
                            'late_duration' => $lateDurationAM,
                            'late_durationPM' => $lateDurationPM,
                            'undertimeAM' => $undertimeAM,
                            'undertimePM' => $undertimePM,
                            'total_late' => $totalHoursLate,
                            'remarks' => $remark,
                            'modify_status'=> $modifyStatus,
                            'hours_late_overall' => $overallTotalHoursLate,
                            'hours_undertime_overall' => $totalundertime,
                            'check_in_time' => $checkInTimer,
                            'employee_idd' => $employee_idd,


                        ];

                        //  session()->put('late_duration', $lateDurationAM);
                    }

                    // Add total hours worked to overall total
                    $overallTotalHours += $totalHoursWorked;
                    $overallTotalLateHours += $overallTotalHoursLate;
                    $overallTotalUndertime += $totalundertime;
                    $overallTotalHoursSum = $overallTotalHoursSumm;
                }
            }
        }

        // Optionally, you can store the $attendanceData and $overallTotalHours in the session or pass it to a view
        session()->put('attendance_data', $attendanceData);

        session()->put('overall_total_hours', $overallTotalHours);

            // 'overallTotalHours' => $overallTotalHours,
            // 'overallTotalLateHours' => $overallTotalLateHours,
            // 'overallTotalUndertime' => $overallTotalUndertime,
            // 'totalHoursTobeRendered' => $totalHoursTobeRendered,
            // 'attendanceData' =>$attendanceData,
            // 'attendanceTimeIn' => $attendanceTimeIn,
            // 'attendanceTimeOut' => $attendanceTimeOut,
            // 'schools' => $schools,
            // 'departments' => $departments,
            // 'schoolToShow' => $this->schoolToShow,
            // 'departmentToShow' => $this->departmentToShow,
            // 'selectedEmployeeToShow' => $this->selectedEmployeeToShow,
            // 'employees' => $employees, // Ensure employees variable is defined if needed
            // 'selectedAttendanceByDate' => $this->selectedAttendanceByDate,
            // 'departmentDisplayWorkingHour' => $departmentDisplayWorkingHour,


                $pdf = \PDF::loadView('generate-pdf-for-payroll-departmental', [
                'overallTotalHours' => $overallTotalHours,
                'overallTotalLateHours' => $overallTotalLateHours,
                'overallTotalUndertime' => $overallTotalUndertime,
                'totalHoursTobeRendered' => $totalHoursTobeRendered,
                'selectedStartDate' => $this->startDate,
                'selectedEndDate' => $this->endDate,
                'selectedMonth' => $this->selectedMonth,
                'selectedYear' => $this->selectedYear,
                'attendanceData' => $attendanceData,
                'attendanceTimeIn' => $attendanceTimeIn,
                'attendanceTimeOut' => $attendanceTimeOut,
                'selectedEmployeeToShow' => $this->selectedEmployeeToShow,
                'departments' => $departments,
            ])->setPaper('letter', 'landscape'); // Set paper size and orientation

             $pdf->save($savePath . '/' . $filename);

            // Download the PDF file with the given filename
             $this->dispatch('export-success');
            session()->flash('success', 'Employees Attendance Report exported to PDF successfully!');
            return response()->download($savePath . '/' . $filename, $filename);
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            dd($e->getMessage()); // Output the error for debugging
        }
    }
    
        public function generateExcelPayroll()
    {
        $this->dispatch('export-start');

        $departments = Department::where('id', $this->selectedDepartment4)->get();
        $department = Department::find($this->selectedDepartment4);

            $currentYear = Carbon::now()->year;
            $currentMonth = $this->selectedYear;
            
           $fullStartDate = Carbon::createFromFormat('m', $this->selectedMonth)->format('F') . " {$this->startDate}";
            $fullEndDate = Carbon::createFromFormat('m', $this->selectedMonth)->format('F') . " {$this->endDate}, {$currentYear}";


            if ($this->startDate && $this->endDate) {
                $selectedStartDate = $fullStartDate;
                $selectedEndDate = $fullEndDate;

                $dateRange = $selectedStartDate . ' to ' . $selectedEndDate;
            } else {
                $dateRange = Carbon::createFromFormat('m', $this->selectedMonth)->format('F') . ', ' . $this->selectedYear;
            }

            if ($department) {
                $departmentName = $department->department_abbreviation;
                $filename = $departmentName . ' - ' . $dateRange . '.xlsx';
            } else {
                $filename = 'Unknown Department - ' . $dateRange . '.xlsx';
            }

            $queryTimeIn = EmployeeAttendanceTimeIn::query()->with(['employee.school', 'employee.department']);
            $queryTimeOut = EmployeeAttendanceTimeOut::query()->with(['employee.school', 'employee.department']);

            if ($this->selectedSchool) {
                $queryTimeIn->whereHas('employee', function (Builder $query) {
                    $query->where('school_id', $this->selectedSchool);
                });
                $queryTimeOut->whereHas('employee', function (Builder $query) {
                    $query->where('school_id', $this->selectedSchool);
                });
                $this->schoolToShow = School::find($this->selectedSchool);
            } else {
                $this->schoolToShow = null;
            }

            if ($this->selectedDepartment4) {
                $queryTimeIn->whereHas('employee', function (Builder $query) {
                    $query->where('department_id', $this->selectedDepartment4);
                });
                $queryTimeOut->whereHas('employee', function (Builder $query) {
                    $query->where('department_id', $this->selectedDepartment4);
                });
                $this->departmentToShow = Department::find($this->selectedDepartment4);
                $employees = Employee::where('department_id', $this->selectedDepartment4)->get();
            } else {
                $this->departmentToShow = null;
                $employees = Employee::all();
            }

            // if ($this->startDate && $this->endDate) {
            //     $queryTimeIn->whereDate('check_in_time', '>=', $this->startDate)
            //                 ->whereDate('check_in_time', '<=', $this->endDate);
            //     $queryTimeOut->whereDate('check_out_time', '>=', $this->startDate)
            //                 ->whereDate('check_out_time', '<=', $this->endDate);
            // }

             $currentMonth = $this->selectedMonth;  // Get the current month
            $currentYear = now()->year;  

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
                            
                $selectedAttendanceByDate = $queryTimeIn->get();// Fetch data and assign to selectedAttendanceByDate
                
                $this->selectedAttendanceByDate = $selectedAttendanceByDate;   
            }
  

                $attendanceTimeIn = $queryTimeIn
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_in_time', $currentMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear)    // Match current year        
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_in_time', 'asc')
                ->get();

            $attendanceTimeOut = $queryTimeOut
                ->where('status', '!=', 'Holiday')
                ->whereMonth('check_out_time', $currentMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear)    // Match current year
                ->orderBy('employee_id', 'asc')
                ->orderBy('check_out_time', 'asc')
                ->get();


            // Construct the attendance data array
            $attendanceData = [];
            $overallTotalHours = 0;
            $overallTotalLateHours = 0;
            $overallTotalUndertime = 0;
            $totalHoursTobeRendered = 0;
            $overallTotalHoursSum = 0;

            foreach ($attendanceTimeIn as $attendance) {
                // Initialize variables for each record
                $hoursWorkedAM = 0;
                $hoursWorkedPM = 0;
                $lateDurationAM = 0;
                $lateDurationPM = 0;
                $undertimeAM = 0;
                $undertimePM = 0;
                $totalHoursLate = 0;
                $totalUndertimeHours = 0;
                $totalLateandUndertime = 0;
                $latePM = 0;
                $lateAM = 0;
                $undertimeAMTotal = 0;
                $undertimePMTotal = 0;
                $totalundertime = 0;
                $totalhoursNeed = 0;
                $totalHoursNeedperDay = 0;

                $totalHoursByDay = [];
                $overallTotalHoursSumm = 0;
                
                $departmentId = $attendance->employee->department_id;

                $workingHoursByDay = DepartmentWorkingHour::select(
                        'day_of_week',
                        'morning_start_time',
                        'morning_end_time',
                        'afternoon_start_time',
                        'afternoon_end_time'
                    )
                    ->where('department_id', $departmentId)
                    ->where('day_of_week', '!=', 0)
                    ->get()
                    ->groupBy('day_of_week');

                

                foreach ($workingHoursByDay as $dayOfWeek => $workingHours) {
                    $totalHours = 0;

                    foreach ($workingHours as $workingHour) {
                        if ($workingHour->morning_start_time && $workingHour->morning_end_time) {
                            $morningStart = Carbon::parse($workingHour->morning_start_time);
                            $morningEnd = Carbon::parse($workingHour->morning_end_time);
                            $totalHours += $morningStart->diffInHours($morningEnd);
                            
                        }

                        if ($workingHour->afternoon_start_time && $workingHour->afternoon_end_time) {
                            $afternoonStart = Carbon::parse($workingHour->afternoon_start_time);
                            $afternoonEnd = Carbon::parse($workingHour->afternoon_end_time);
                            $totalHours += $afternoonStart->diffInHours($afternoonEnd);
                        }
                    }

                    $totalHoursByDay[$dayOfWeek] = $totalHours;
                    $overallTotalHoursSumm += $totalHours;
                }

                // foreach ($totalHoursByDay as $dayOfWeek => $totalHours) {
                //     echo "Day of Week: $dayOfWeek\n";
                //     echo "Total Working Hours: $totalHours hours\n";
                //     echo "------------------------\n";
                // }
                // echo "Overall Total Working Hours: $overallTotalHours hours\n";

                $now = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
                // Extract date and time from check-in


                // Find corresponding check-out time
                $checkOut = $attendanceTimeOut->where('employee_id', $attendance->employee_id)
                                                ->where('check_out_time', '>=', $attendance->check_in_time)
                                                ->first();

                if ($checkOut) {
                    $checkOutDateTime = new DateTime($checkOut->check_out_time);


                    // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                    //                                                 ->where('day_of_week', '!=', 6)
                    //                                                 ->first();

                        $checkInDateTime = new DateTime($attendance->check_in_time);
                        $checkInDate = $checkInDateTime->format('Y-m-d');
                        $checkInTime = $checkInDateTime->format('H:i:s'); // Extracted time part
                        $dayOfWeek = $checkInDateTime->format('w');

                    // $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                    //                                                 ->where('day_of_week', '!=', 0)
                    //                                                 ->first();

                    $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $attendance->employee->department_id)
                                                    ->where('day_of_week', $dayOfWeek)
                                                    ->where('day_of_week', '!=', 0)
                                                    ->first();

                    
                    


                    
                    if ($departmentWorkingHour) 
                    {   

                    
                        $mS = $departmentWorkingHour->morning_start_time;
                        $morningStartTime = clone $checkInDateTime;
                        $morningStartTime->setTime(
                            (int) date('H', strtotime($mS)),
                            (int) date('i', strtotime($mS)),
                            (int) date('s', strtotime($mS))
                        );

                        $morStart = $morningStartTime->setTime(
                            (int) date('H', strtotime($mS)),
                            (int) date('i', strtotime($mS)),
                            (int) date('s', strtotime($mS))
                        );

                        $mE = $departmentWorkingHour->morning_end_time;
                        $morningEndTime = clone $checkInDateTime;
                        $morningEndTime->setTime(
                            (int) date('H', strtotime($mE)),
                            (int) date('i', strtotime($mE)),
                            (int) date('s', strtotime($mE))
                        );

                        $aS = $departmentWorkingHour->afternoon_start_time;
                        $afternoonStartTime = clone $checkInDateTime;
                        $afternoonStartTime->setTime(
                            (int) date('H', strtotime($aS)),
                            (int) date('i', strtotime($aS)),
                            (int) date('s', strtotime($aS))
                        );

                        
                        $aE = $departmentWorkingHour->afternoon_end_time;
                            $afternoonEndTime = clone $checkInDateTime;
                            $afternoonEndTime->setTime(
                                (int) date('H', strtotime($aE)),
                                (int) date('i', strtotime($aE)),
                                (int) date('s', strtotime($aE))
                            );
                        
                        $morningStartTimew = $departmentWorkingHour->morning_start_time;
                        $morningEndTimew = $departmentWorkingHour->morning_end_time;
                        $afternoonStartTimew = $departmentWorkingHour->afternoon_start_time;
                        $afternoonEndTimew = $departmentWorkingHour->afternoon_end_time;

                            // Convert times to Carbon instances
                        $morningStartw = new DateTime($morningStartTimew);
                        $morningEndw = new DateTime($morningEndTimew);
                        $afternoonStartw = new DateTime($afternoonStartTimew);
                        $afternoonEndw = new DateTime($afternoonEndTimew);

                        // Calculate the duration in minutes for morning and afternoon
                        $morningInterval = $morningStartw->diff($morningEndw);
                        $morningDurationInMinutes = ($morningInterval->h * 60) + $morningInterval->i;
                        $afternoonInterval = $afternoonStartw->diff($afternoonEndw);
                        $afternoonDurationInMinutes = ($afternoonInterval->h * 60) + $afternoonInterval->i;

                        // Convert minutes to hours
                        $morningDuration = $morningDurationInMinutes / 60;
                        $afternoonDuration = $afternoonDurationInMinutes / 60;
                        // Calculate total hours needed
                        $totalHoursNeed = $morningDuration + $afternoonDuration;
                        $totalHoursTobeRendered = $totalHoursNeed;
                        $totalHoursNeedperDay = $totalHoursNeed;

                                     $currentMonth = $this->selectedMonth;  // Get the current month
                                    $currentYear = now()->year;  

                        if ($this->startDate && $this->endDate) {
                            $employeeId = $attendance->employee_id; // Assuming you have this from $attendance

                            // Determine if the start date and end date are the same
                            $isSameDate = $this->startDate === $this->endDate; // Adjust if necessary for your date format
                            // $startDate = Carbon::parse($this->startDate)->startOfDay(); // Start of the selected start date
                            // $endDate = Carbon::parse($this->endDate)->endOfDay(); // End of the selected end date
                            $startDate = Carbon::parse("{$currentYear}-{$currentMonth}-{$this->startDate}")->startOfDay();
                            // $endDate = Carbon::parse($this->endDate)->endOfDay(); // End of the selected end date
                            $endDate = Carbon::parse("{$currentYear}-{$currentMonth}-{$this->endDate}")->startOfDay();

                            if ($isSameDate) {
                                // If the start date and end date are the same, only consider that specific day
                                $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                                    ->where('employee_id', $employeeId)
                                    ->whereDay('check_in_time', $this->startDate)
                                    ->first();
                            } else {
                                // If the start date and end date are different, consider the range
                                // $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                                //     ->where('employee_id', $employeeId)
                                //     ->whereBetween('check_in_time', [$startDate, $endDate])
                                //     ->first();
                                $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(employees_time_in_attendance.check_in_time)) as unique_check_in_days'))
                                            ->join('employees', 'employees_time_in_attendance.employee_id', '=', 'employees.id')
                                            ->join('working_hour', function($join) {
                                                $join->on('employees.department_id', '=', 'working_hour.department_id');
                                            })
                                            ->where('employees_time_in_attendance.employee_id', $employeeId)
                                            ->whereNotIn('employees_time_in_attendance.status', ['Absent', 'AWOL', 'On Leave'])
                                            ->whereNotIn('working_hour.day_of_week', [0, 6])
                                            ->whereBetween('check_in_time', [$startDate, $endDate]) // Exclude Saturday (6) and Sunday (7)
                                            ->first();
                                        

                            }

                            // Get the unique check-in days count
                            $uniqueCheckInDays = (int) $checkInCount->unique_check_in_days;
                            
                            // Calculate total hours to be rendered
                            $totalHoursTobeRendered = $totalHoursNeed * $uniqueCheckInDays;
                        } else {
                            
                            $employeeId = $attendance->employee_id; // Assuming you have this from $attendance
                            

                            $noww = new DateTime('now', new DateTimeZone('Asia/Taipei'));
                            $currentDatee = $noww->format('Y-m-d') . ' 00:00:00';

                            $checkInCount = EmployeeAttendanceTimeIn::select(DB::raw('COUNT(DISTINCT DATE(check_in_time)) as unique_check_in_days'))
                                ->where('employee_id', $employeeId)
                                ->where('check_in_time', '<>', $currentDatee)
                                ->whereNotIn('status', ['Absent', 'AWOL', 'On Leave'])
                                ->first();

                            $uniqueCheckInDays = (int) $checkInCount->unique_check_in_days;
                            $totalHoursTobeRendered = $totalHoursNeed * $uniqueCheckInDays;

                    
                        }
                        
                        $gracePeriodFirst = GracePeriod::first();
                        if($gracePeriodFirst){
                            $gracePeriodValue = (float) $gracePeriodFirst->grace_period;
                            // AM Shift Calculation  for 15 mins interval of declaring late
                            if ($checkInDateTime < $morningEndTime) {
                                $effectiveCheckInTime = max($checkInDateTime, $morningStartTime);
                                $effectiveCheckOutTime = min($checkOutDateTime, $morningEndTime);
                                if ($effectiveCheckInTime < $effectiveCheckOutTime) {
                                    $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                    // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60);
                                    $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                    
                                    // Calculate late duration for AM
                                    

                                    // Check if there's only one check-in and check-out in the same day
                                    // if ($checkInDateTime->format('Y-m-d') == $checkOutDateTime->format('Y-m-d')) {
                                    //     $hoursWorkedAM = 0;
                                    // }

                                    $dateKey1 = $checkInDateTime->format('Y-m-d');
                                    $dateKey2 = $checkOutDateTime->format('Y-m-d');

                                    // Fetch counts of check-ins and check-outs for the specified dates
                                    $checkOuttCount = EmployeeAttendanceTimeOut::select(DB::raw('DATE(check_out_time) as date, COUNT(*) as count'))
                                        ->where('employee_id', $employeeId)
                                        ->groupBy(DB::raw('DATE(check_out_time)'))
                                        ->pluck('count', 'date');

                                    $checkInnCount = EmployeeAttendanceTimeIn::select(DB::raw('DATE(check_in_time) as date, COUNT(*) as count'))
                                        ->where('employee_id', $employeeId)
                                        ->groupBy(DB::raw('DATE(check_in_time)'))
                                        ->pluck('count', 'date');

                                    // Fetch the first check-in and check-out times for the specified date
                                    $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                        ->whereDate('check_in_time', $dateKey1)
                                        ->orderBy('check_in_time', 'asc')
                                        ->first();

                                    $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                        ->whereDate('check_out_time', $dateKey2)
                                        ->orderBy('check_out_time', 'asc')
                                        ->first();

                                    $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                        ->whereDate('check_in_time', $dateKey1)
                                        ->orderBy('check_in_time', 'asc')
                                        ->skip(1)
                                        ->first();

                                    $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                        ->whereDate('check_out_time', $dateKey2)
                                        ->orderBy('check_out_time', 'asc')
                                        ->skip(1)
                                        ->first();

                                    // Calculate PM hours if applicable
                                    if ($checkInnCount->get($dateKey1, 0) == 1 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                        
                                        if ($firstCheckIn && $firstCheckOut) {
                                            $checkInTime = new DateTime($firstCheckIn->check_in_time);
                                            $checkOutTime = new DateTime($firstCheckOut->check_out_time);

                                            // Ensure check-in is PM and check-out is also PM
                                            if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'pm') {
                                                $amEndTime = new DateTime($dateKey1 . ' 14:00:00');
                                                if ($checkOutTime < $amEndTime) {
                                                // Calculate the interval between the check-out time and 1:00 PM
                                                //so naa cutoff ang checkout sa buntag 1 pwedi i set $amEndTime
                                                // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                                // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                                
                                                $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);

                                                }else{
                                                    $hoursWorkedAM = 0;
                                                }
                                            }
                                            else {
                                                // $hoursWorkedAM = 0;
                                            }
                                        } else {
                                            $hoursWorkedAM = 0;
                                        }
                                    } else {
                                        
                                            if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                                if ($firstCheckIn && $firstCheckOut && $secondCheckIn && $secondCheckOut) {
                                                    
                                                    $checkInTime = new DateTime($firstCheckIn->check_in_time);
                                                    $checkOutTime = new DateTime($firstCheckOut->check_out_time);

                                                    $checkInTime2 = Carbon::parse($secondCheckIn->check_in_time);
                                                    $checkOutTime2 = Carbon::parse($secondCheckOut->check_out_time);

                                                    if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'am' && $checkInTime2->format('a') === 'pm' && $checkOutTime2->format('a') === 'am') { 
                                                        $hoursWorkedAM = 0;
                                                    }
                                                }
                                            }
                                        

                                        // if ($checkInnCount->get($dateKey1, 0) == 1 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                        //     $hoursWorkedAM = 0;
                                        // }

                                    }


                                    if ($checkInDateTime > $morningStartTime) {
                                        // Define the latest allowed check-in time with a 15-minute grace period
                                        // $latestAllowedCheckInAM = clone $morningStartTime;
                                        // $latestAllowedCheckInAM->add(new DateInterval('PT15M'));
                                        // Rounds to nearest integer

                                        
                                        $gracePeriodMinutes = $gracePeriodValue * 60;
                                        $gracePeriodMinutes = round($gracePeriodMinutes);
                                        $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                        
                                        // Clone the original time and add the interval
                                        $latestAllowedCheckInAM = clone $morningStartTime;
                                        $latestAllowedCheckInAM->add(new DateInterval($intervalSpec));

                                        // Check if the check-in time is beyond the 15-minute grace period
                                        if ($checkInDateTime > $latestAllowedCheckInAM ) {

                                            // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            // $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                            // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            // $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                            // Calculate the late interval starting from the grace period end

                                            // $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            // // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60);
                                            // $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                            

                                            $lateIntervalAM = $checkInDateTime->diff($latestAllowedCheckInAM);
                                            $lateDurationAM = ($lateIntervalAM->h * 60) + $lateIntervalAM->i + ($lateIntervalAM->s / 60);
                                            
                                            // Calculate the late duration in hours, minutes, and seconds
                                            
                                            $lateAM = $lateIntervalAM->h + ($lateIntervalAM->i / 60) + ($lateIntervalAM->s / 3600);
                                            
                                            


                                        } else {
                                            $lateIntervalAM = $checkInDateTime->diff($latestAllowedCheckInAM);
                                            $lateDurationAM = ($lateIntervalAM->h * 60) + $lateIntervalAM->i + ($lateIntervalAM->s / 60);
                                            
                                            // Calculate the late duration in hours, minutes, and seconds
                                            
                                            $lateAM = $lateIntervalAM->h + ($lateIntervalAM->i / 60) + ($lateIntervalAM->s / 3600);
                                            
                                            // Calculate hours worked in the AM
                                            $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            $hoursWork = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);

                                            // Calculate the difference in minutes (or hours) from $checkInDateTime to $morningStartTime
                                            $intervalToMorningStart = $checkInDateTime->diff($morningStartTime);
                                            $dataInMinutes = $intervalToMorningStart->h * 60 + $intervalToMorningStart->i + ($intervalToMorningStart->s / 60);
                                            $dataInHours = $dataInMinutes / 60;

                                            $hoursWorkedAM = $hoursWorkedAM + $dataInHours;
                                            
                                            $lateDurationAM = 0;
                                            $lateAM = 0;
                                            
                                        }
                                            
                                        // if($checkInDateTime < $latestAllowedCheckInAM)
                                        // {
                                            
                                        //     // $intervalAM = $morningStartTime->diff($effectiveCheckOutTime);
                                            
                                        //     // // Convert intervals to total minutes
                                        //     // $intervalMinutesAM = ($intervalAM->h * 60) + $intervalAM->i + ($intervalAM->s / 60);

                                        //     // // Calculate hours worked in AM
                                        //     // $hoursWorkedAM = $intervalMinutesAM / 60; // Convert total minutes to hours
                                        //     // $hoursWorkedAM += 0.25;
                                        //     // $lateDurationAM = 0;
                                        //     // $lateAM = 0;
                                            
                                        // }
                                    } else {
                                        // If check-in is on time or early, set late duration to 0
                                            $lateDurationAM = 0;
                                            $lateAM = 0;
                                            
                                    }

                                    if ($lateDurationAM > 0 ) {
                                        // $hoursWorkedAM += 0.25; // Subtract 0.25 hours (15 minutes) if late
                                        
                                        $hoursWorkedAM += $gracePeriodValue;
                                        
                                    }
                                    // if ($lateDurationAM > 0 ) {
                                    //     // $hoursWorkedAM += 0.25; // Subtract 0.25 hours (15 minutes) if late
                                        
                                    //     $hoursWorkedAM += $gracePeriodValue;
                                        
                                    // }

                                    if ($lateDurationAM > 0 && $hoursWorkedAM == $gracePeriodValue) {
                                        $hoursWorkedAM -= $gracePeriodValue;
                                        $lateDurationAM = 0;
                                        $lateAM = 0; // Subtract 0.25 hours (15 minutes) if late
                                    }

                                    
                                    // if($lateDurationAM > 0 && $hoursWorkedAM > $gracePeriodValue){
                                    //          $hoursWorkedAM += $gracePeriodValue;
                                    // }

                                    // if ($lateDurationAM > 0 && $hoursWorkedAM == 0.25) {
                                    //     $hoursWorkedAM -= 0.25;
                                    //     $lateDurationAM = 0;
                                    //     $lateAM = 0; // Subtract 0.25 hours (15 minutes) if late
                                    // }   
                                    
                                    // if ($checkInDateTime <= $latestAllowedCheckInAM) {
                                    //     // Calculate the total hours worked considering the effective check-in time and the morning end time
                                    //     $effectiveCheckInTime = max($checkInDateTime, $morningStartTime);
                                    //     $effectiveCheckOutTime = min($checkOutDateTime, $morningEndTime);
                                    //     $intervalAM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                    //     $hoursWorkedAM = $intervalAM->h + ($intervalAM->i / 60) + ($intervalAM->s / 3600);
                                    // }


                                }

                                    $scheduledDiff = $morningStartTime->diff($morningEndTime);
                                    $scheduledAMMinutes = ($scheduledDiff->h * 60) + $scheduledDiff->i + ($scheduledDiff->s / 60);

                                    // Calculate actual worked time up to the morning end time including seconds
                                    if ($effectiveCheckOutTime < $morningEndTime) {
                                        $actualDiff = $effectiveCheckOutTime->diff($morningStartTime);
                                    } else {
                                        $actualDiff = $morningEndTime->diff($morningStartTime);
                                    }
                                    $actualMinutesUpToEnd = ($actualDiff->h * 60) + $actualDiff->i + ($actualDiff->s / 60);
                                        $undertimeAMTotal = max(0, $scheduledAMMinutes - $actualMinutesUpToEnd);
                                    // Calculate undertime in minutes
                                    $undertimeAM = max(0, $scheduledAMMinutes - $actualMinutesUpToEnd);
                        
                            }   
                        

                            // PM Shift Calculation
                            if ($checkInDateTime < $afternoonEndTime && $checkOutDateTime > $afternoonStartTime) {
                                $effectiveCheckInTime = max($checkInDateTime, $afternoonStartTime);
                                $effectiveCheckOutTime = min($checkOutDateTime, $afternoonEndTime);
                                if ($effectiveCheckInTime < $effectiveCheckOutTime) {
                                    $intervalPM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                    // $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                    // Calculate late duration for PM
                                    // $latestAllowedCheckInPM = clone $afternoonStartTime;
                                    // $latestAllowedCheckInPM->add(new DateInterval('PT15M'));

                                        $gracePeriodMinutes = $gracePeriodValue * 60;
                                        $gracePeriodMinutes = round($gracePeriodMinutes);
                                        $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                        
                                        // Clone the original time and add the interval
                                        $latestAllowedCheckInPM = clone $afternoonStartTime;
                                        $latestAllowedCheckInPM->add(new DateInterval($intervalSpec));

                                    // Check if there's only one check-in and check-out in the same day
                                    // if ($checkInDateTime->format('Y-m-d') == $checkOutDateTime->format('Y-m-d')) {
                                    //     $hoursWorkedPM = 0;
                                    // }

                                    // Convert the check-in and check-out date to the format 'Y-m-d'
                                    $dateKey1 = $checkInDateTime->format('Y-m-d');
                                    $dateKey2 = $checkOutDateTime->format('Y-m-d');

                                    // Fetch counts of check-ins and check-outs for the specified dates
                                    $checkOuttCount = EmployeeAttendanceTimeOut::select(DB::raw('DATE(check_out_time) as date, COUNT(*) as count'))
                                        ->where('employee_id', $employeeId)
                                        ->groupBy(DB::raw('DATE(check_out_time)'))
                                        ->pluck('count', 'date');

                                    $checkInnCount = EmployeeAttendanceTimeIn::select(DB::raw('DATE(check_in_time) as date, COUNT(*) as count'))
                                        ->where('employee_id', $employeeId)
                                        ->groupBy(DB::raw('DATE(check_in_time)'))
                                        ->pluck('count', 'date');

                                    // Fetch the first check-in and check-out times for the specified date
                                    $firstCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                        ->whereDate('check_in_time', $dateKey1)
                                        ->orderBy('check_in_time', 'asc')
                                        ->first();

                                    $firstCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                        ->whereDate('check_out_time', $dateKey2)
                                        ->orderBy('check_out_time', 'asc')
                                        ->first();

                                    $secondCheckIn = EmployeeAttendanceTimeIn::where('employee_id', $employeeId)
                                        ->whereDate('check_in_time', $dateKey1)
                                        ->orderBy('check_in_time', 'asc')
                                        ->skip(1)
                                        ->first();

                                    $secondCheckOut = EmployeeAttendanceTimeOut::where('employee_id', $employeeId)
                                        ->whereDate('check_out_time', $dateKey2)
                                        ->orderBy('check_out_time', 'asc')
                                        ->skip(1)
                                        ->first();

                                    // Calculate PM hours if applicable
                                    
                                    if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                        if ($secondCheckIn && $secondCheckOut) {
                                            // Convert check-in and check-out times to Carbon instances
                                            $checkInTime = Carbon::parse($secondCheckIn->check_in_time);
                                            $checkOutTime = Carbon::parse($secondCheckOut->check_out_time);

                                            // Ensure check-in is PM and check-out is also PM
                                            if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'pm') {
                                                $timeStartTimeSecond = Carbon::parse($afternoonStartTime)->format('H:i:s');
                                                $secondTime = Carbon::parse($secondCheckIn->check_in_time)->format('H:i:s');
                                                
                                                //dd($afternoonStartTime > $secondTime);
                                                // if($afternoonStartTime > $secondTime){
                                                //     $hoursWorkedPM = 0;
                                                // } else {
                                                $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                                    // if($afternoonStartTime > $secondTime){
                                                //     $hoursWorkedPM = 0;
                                                // } else {
                                                // }
                                            } else {
                                                $hoursWorkedPM = 0;
                                            }
                                        } else {
                                            $hoursWorkedPM = 0;
                                        }
                                    } else {
                                        // Set to 0 if counts are not both 2
                                            
                                        if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                            $hoursWorkedPM = 0;
                                        } 
                                        else 
                                        {
                                            if ($firstCheckIn && $firstCheckOut) {
                                                // Convert check-in and check-out times to Carbon instances
                                                $checkInTime = Carbon::parse($firstCheckIn->check_in_time);
                                                $checkOutTime = Carbon::parse($firstCheckOut->check_out_time);

                                                // Ensure check-in is PM and check-out is also PM
                                                if ($checkInTime->format('a') === 'am' && $checkOutTime->format('a') === 'pm') {
                                                    $hoursWorkedPM = 0;
                                                } else if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'am') {
                                                    $hoursWorkedPM = 0;
                                                }else {
                                                    $hoursWorkedPM = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);
                                                }
                                            }
                                        }
    
                                    }



                                    
                                    if ($checkInDateTime > $afternoonStartTime) {

                                        // $latestAllowedCheckInPM = clone $afternoonStartTime;
                                        // $latestAllowedCheckInPM->add(new DateInterval('PT15M'));

                                        $gracePeriodMinutes = $gracePeriodValue * 60;
                                        $gracePeriodMinutes = round($gracePeriodMinutes);
                                        $intervalSpec = 'PT' . $gracePeriodMinutes . 'M';
                                        
                                        // Clone the original time and add the interval
                                        $latestAllowedCheckInPM = clone $afternoonStartTime;
                                        $latestAllowedCheckInPM->add(new DateInterval($intervalSpec));

                                        // $lateIntervalPM = $checkInDateTime->diff($afternoonStartTime);
                                        // $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                        // $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);

                                        // Check if the check-in time is beyond the 15-minute grace period
                                        if ($checkInDateTime > $latestAllowedCheckInPM ) {
                                            // Calculate the late interval starting from the grace period end
                                            $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                            $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                            // Calculate the late duration in hours, minutes, and seconds
                                            
                                            $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                            

                                        } else {
                                            // If within the grace period, set late duration to 0
                                            // $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                            // $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                            // // Calculate the late duration in hours, minutes, and seconds
                                            
                                            // $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                            // $lateDurationPM = 0;
                                            // $latePM = 0;

                                            $lateIntervalPM = $checkInDateTime->diff($latestAllowedCheckInPM);
                                            $lateDurationPM = ($lateIntervalPM->h * 60) + $lateIntervalPM->i + ($lateIntervalPM->s / 60);
                                            
                                            // Calculate the late duration in hours, minutes, and seconds
                                            
                                            $latePM = $lateIntervalPM->h + ($lateIntervalPM->i / 60) + ($lateIntervalPM->s / 3600);
                                            
                                            // Calculate hours worked in the PM
                                            $intervalPM = $effectiveCheckInTime->diff($effectiveCheckOutTime);
                                            $hoursWork = $intervalPM->h + ($intervalPM->i / 60) + ($intervalPM->s / 3600);

                                            // Calculate the difference in minutes (or hours) from $checkInDateTime to $morningStartTime
                                            $intervalToAfternoonStart = $checkInDateTime->diff($afternoonStartTime);
                                            $dataInMinutes = $intervalToAfternoonStart->h * 60 + $intervalToAfternoonStart->i + ($intervalToAfternoonStart->s / 60);
                                            $dataInHours = $dataInMinutes / 60;

                                            if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 2) {
                                                if ($secondCheckIn && $secondCheckOut) {
                                                    // Convert check-in and check-out times to Carbon instances
                                                    $checkInTime = Carbon::parse($secondCheckIn->check_in_time);
                                                    $checkOutTime = Carbon::parse($secondCheckOut->check_out_time);

                                                    // Ensure check-in is PM and check-out is also PM
                                                    if ($checkInTime->format('a') === 'pm' && $checkOutTime->format('a') === 'pm') {
                                                        $timeStartTimeSecond = Carbon::parse($afternoonStartTime)->format('H:i:s');
                                                        $secondTime = Carbon::parse($secondCheckIn->check_in_time)->format('H:i:s');
                                                        
                                                        //dd($afternoonStartTime > $secondTime);
                                                        // if($afternoonStartTime > $secondTime){
                                                        //     $hoursWorkedPM = 0;
                                                        // } else {
                                                    $hoursWorkedPM = $hoursWorkedPM + $dataInHours;

                                                            // if($afternoonStartTime > $secondTime){
                                                        //     $hoursWorkedPM = 0;
                                                        // } else {
                                                        // }
                                                    } else {
                                                        $hoursWorkedPM = 0;
                                                    }
                                                } else {
                                                    $hoursWorkedPM = 0;
                                                }
                                            } else {
                                                // Set to 0 if counts are not both 2
                                                
                                                if ($checkInnCount->get($dateKey1, 0) == 2 && $checkOuttCount->get($dateKey2, 0) == 1) {
                                                    $hoursWorkedPM = 0;
                                                }
                                            }
                                                    

                                                                                
                                            
                                            
                                            $lateDurationPM = 0;
                                            $latePM = 0;
                                            
                                            
                                        }

                                        // if($checkInDateTime <= $latestAllowedCheckInPM)
                                        // {
                                            
                                        //     $intervalPM = $afternoonStartTime->diff($effectiveCheckOutTime);
                                            
                                        //     // Convert intervals to total minutes
                                        //     $intervalMinutesPM = ($intervalPM->h * 60) + $intervalPM->i + ($intervalPM->s / 60);

                                        //     // Calculate hours worked in AM
                                        //     $hoursWorkedPM= $intervalMinutesPM / 60; // Convert total minutes to hours
                                            

                                        // }

                                    } else {
                                        // If check-in is on time or early, set late duration to 0
                                            $lateDurationPM = 0;
                                            $latePM = 0;
                                            
                                    }

                                    if ($lateDurationPM > 0) {
                                        $hoursWorkedPM += $gracePeriodValue; // Subtract 0.25 hours (15 minutes) if late
        
                                    }

                                    if ($lateDurationPM > 0 && $hoursWorkedPM == $gracePeriodValue) {
                                        $hoursWorkedPM -= $gracePeriodValue;
                                        $lateDurationPM = 0;
                                        $latePM = 0; // Subtract 0.25 hours (15 minutes) if late
                                    }

                                    
                                    

                                    //  if ($lateDurationPM > 0) {
                                    //     $hoursWorkedPM += 0.25; // Subtract 0.25 hours (15 minutes) if late
        
                                    // }
                                    
                                }

                                

                                $scheduledPMDiff = $afternoonStartTime->diff($afternoonEndTime);
                                $scheduledPMMinutes = ($scheduledPMDiff->h * 60) + $scheduledPMDiff->i + ($scheduledPMDiff->s / 60);

                                // Calculate actual worked time up to the afternoon end time including seconds
                                if ($effectiveCheckOutTime < $afternoonEndTime) {
                                    $actualPMDiff = $effectiveCheckOutTime->diff($afternoonStartTime);
                                } else {
                                    $actualPMDiff = $afternoonEndTime->diff($afternoonStartTime);
                                }
                                $actualMinutesUpToEndPM = ($actualPMDiff->h * 60) + $actualPMDiff->i + ($actualPMDiff->s / 60);
                                $undertimePMTotal = max(0, $scheduledPMMinutes - $actualMinutesUpToEndPM);
                                // Calculate undertime in minutes
                                $undertimePM = max(0, $scheduledPMMinutes - $actualMinutesUpToEndPM);

                            }
                        }

                        

                        // Calculate total hours worked
                        $totalHoursWorked = $hoursWorkedAM + $hoursWorkedPM;
                        
                        $totalHoursLate = $lateDurationAM + $lateDurationPM;
                        $totalUndertimeHours = $undertimeAM + $undertimePM;
                        $overallTotalHoursLate = $lateAM + $latePM;
                        $totalundertime = $undertimeAMTotal + $undertimePMTotal;

                        // $totalhoursNeed = $morningDuration + $afternoonDuration;
        
                        // Determine remark based on lateness
                        $remark = ($lateDurationAM > 0 || $lateDurationPM > 0) ? 'Late' : 'Present';

                        $modifyStatus = $attendance->status;

            

                        // Prepare the key for $attendanceData
                        $key = $attendance->employee_id . '-' . $checkInDate;

                        $employee_idd = $attendance->employee->employee_id;
                        $employee_id = $attendance->employee_id;
                        $employeeLastname = $attendance->employee->employee_lastname;
                        $employeeFirstname = $attendance->employee->employee_firstname;
                        $employeeMiddlename = $attendance->employee->employee_middlename;
                        $checkInTimer = $attendance->check_in_time;
                        $department = $attendance->employee->department->department_abbreviation;
                        $currentYear = Carbon::now()->year;
                        $currentMonth = $this->selectedYear;
                        
                        // Check if this entry already exists in $attendanceData
                        if (isset($attendanceData[$key])) {
                            // Update existing entry
                            
                            $attendanceData[$key]->hours_perDay = $totalHoursNeedperDay;
                            $attendanceData[$key]->hours_workedAM += $hoursWorkedAM;
                            $attendanceData[$key]->hours_workedPM += $hoursWorkedPM;
                            $attendanceData[$key]->total_hours_worked += $totalHoursWorked;
                            $attendanceData[$key]->total_hours_late += $totalHoursLate;
                            $attendanceData[$key]->late_duration += $lateDurationAM;
                            $attendanceData[$key]->late_durationPM += $lateDurationPM;
                            $attendanceData[$key]->undertimeAM += $undertimeAM;
                            $attendanceData[$key]->undertimePM += $undertimePM;
                            $attendanceData[$key]->total_late += $totalHoursLate;
                            $attendanceData[$key]->remarks = $remark;
                            $attendanceData[$key]->modify_status = $modifyStatus;
                            $attendanceData[$key]->employee_idd = $employee_idd;
                            $attendanceData[$key]->employee_id = $employee_id;
                            $attendanceData[$key]->employee_lastname = $employeeLastname;
                            $attendanceData[$key]->employee_firstname = $employeeFirstname;
                            $attendanceData[$key]->employee_middlename = $employeeMiddlename;
                            $attendanceData[$key]->hours_late_overall += $overallTotalHoursLate;
                            $attendanceData[$key]->hours_undertime_overall += $totalundertime;
                            $attendanceData[$key]->check_in_time = $checkInTimer;
                            $attendanceData[$key]->department_abbreviation = $department;
                            $attendanceData[$key]->startDate = $this->startDate;
                            $attendanceData[$key]->endDate = $this->endDate;
                            $attendanceData[$key]->selectedMonth = $this->selectedMonth;
                            $attendanceData[$key]->selectedYear = $this->selectedYear;
                            $attendanceData[$key]->currentMonth = $this->selectedMonth;
                            $attendanceData[$key]->currentYear = $this->selectedYear;


                            // dd($attendanceData[$key]->undertimeAM += $undertimeAM);
                        } else {
                            // Create new entry
                            $attendanceData[$key] = (object) [
                                'hours_perDay' => $totalHoursNeedperDay,
                                'employee_id' => $attendance->employee_id,
                                'employee_lastname' => $employeeLastname,
                                'employee_firstname' => $employeeFirstname,
                                'employee_middlename' => $employeeMiddlename,
                                'worked_date' => $checkInDate,
                                'hours_workedAM' => $hoursWorkedAM,
                                'hours_workedPM' => $hoursWorkedPM,
                                'total_hours_worked' => $totalHoursWorked,
                                'total_hours_late' => $totalHoursLate,
                                'late_duration' => $lateDurationAM,
                                'late_durationPM' => $lateDurationPM,
                                'undertimeAM' => $undertimeAM,
                                'undertimePM' => $undertimePM,
                                'total_late' => $totalHoursLate,
                                'remarks' => $remark,
                                'modify_status'=> $modifyStatus,
                                'hours_late_overall' => $overallTotalHoursLate,
                                'hours_undertime_overall' => $totalundertime,
                                'check_in_time' => $checkInTimer,
                                'employee_idd' => $employee_idd,
                                'department_abbreviation' => $department,
                                'startDate' => $this->startDate,
                                'startDate' => $this->endDate,
                                'selectedMonth' => $this->selectedMonth,
                                'selectedYear' => $this->selectedYear,
                                'currentMonth' => $this->selectedMonth,
                                'currentYear' => $this->selectedYear,



                            ];
                            

                            //  session()->put('late_duration', $lateDurationAM);
                        }

                        // Add total hours worked to overall total
                        $overallTotalHours += $totalHoursWorked;
                        $overallTotalLateHours += $overallTotalHoursLate;
                        $overallTotalUndertime += $totalundertime;
                        $overallTotalHoursSum = $overallTotalHoursSumm;
                    }
                }
            }
            $currentMonths = $this->selectedMonth;
            $this->dispatch('export-success');
            session()->flash('success', 'Attendance Report downloaded successfully!');
            $export = new AttendanceExportForPayroll($attendanceData, $currentMonths);

            return Excel::download($export, $filename);

    
    }




    public function updateEmployees()
    {
        if ($this->selectedSchool) {
            $this->departmentsToShow = Department::where('school_id', $this->selectedSchool)->get();
        } else {
            $this->departmentsToShow = collect();
        }

        $this->selectedDepartment4 = null;
        $this->departmentToShow = null;
        $this->startDate = null; // Reset start date
        $this->endDate = null; // Reset end date
    }

    public function updateEmployeesByDepartment()
    {
        if ($this->selectedDepartment4 && $this->selectedSchool) {
            $this->departmentToShow = Department::where('id', $this->selectedDepartment4)
                ->where('school_id', $this->selectedSchool)
                ->first();
        } else {
            $this->departmentToShow = null;
            $this->startDate = null; // Reset start date
            $this->endDate = null; // Reset end date
        }
    }

    public function updateAttendanceByEmployee()
    {
        if ($this->selectedEmployee) {
            $this->attendancesToShow = EmployeeAttendanceTimeIn::where('employee_id', $this->selectedEmployee)->get();
            $this->attendancesToShow = EmployeeAttendanceTimeOut::where('employee_id', $this->selectedEmployee)->get();
        } else {
            $this->attendancesToShow = collect();
            $this->startDate = null; // Reset start date
            $this->endDate = null; // Reset end date
        }
    }

public function updateAttendanceByDateRange()
{
    if ($this->startDate && $this->endDate) {
        // Base query for EmployeeAttendanceTimeIn with left join to EmployeeAttendanceTimeOut
        $queryTimeIn = EmployeeAttendanceTimeIn::query()
            ->with(['employee.school', 'employee.department'])
            ->where('employee_id', $this->selectedEmployee)
            ->whereDate('check_in_time', '>=', $this->startDate)
            ->whereDate('check_in_time', '<=', $this->endDate);

        $this->selectedAttendanceByDate = $queryTimeIn->get();
                $this->dispatch('reload-success');  
    } else {
        $this->selectedAttendanceByDate = collect(); // Empty collection if no date range selected
    }
}


    protected function applySearchFiltersIn($queryTimeIn)
    {
        return $queryTimeIn->whereHas('employee', function (Builder $query) {
            $query->where('employee_id', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_middlename', 'like', '%' . $this->search . '%')
                    ->orWhereHas('department', function (Builder $query) {
                    $query->where('department_abbreviation', 'like', '%' . $this->search . '%')
                        ->orWhere('department_name', 'like', '%' . $this->search . '%');
                });
        });
    }

    protected function applySearchFiltersOut($queryTimeOut)
    {
        return $queryTimeOut->whereHas('employee', function (Builder $query) {
            $query->where('employee_id', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_middlename', 'like', '%' . $this->search . '%')
                    ->orWhereHas('department', function (Builder $query) {
                    $query->where('department_abbreviation', 'like', '%' . $this->search . '%')
                        ->orWhere('department_name', 'like', '%' . $this->search . '%');
                });
        });
    }
}