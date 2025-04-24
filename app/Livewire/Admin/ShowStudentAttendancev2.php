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

class ShowStudentAttendancev2 extends Component
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
        
            if ($this->selectedCourse5) {
                $query->where('course_id', $this->selectedCourse5);
                $this->selectedCourseToShow = Course::find($this->selectedCourse5);
    
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
                // $queryTimeIn->whereDate('check_in_time', '>=', 1)
                //             ->whereDate('check_in_time', '<=', 31);

                // $queryTimeOut->whereDate('check_out_time', '>=', 1)
                //             ->whereDate('check_out_time', '<=', 31);
            }
            $currentYear = now()->year; 
            $queryTimeIn->whereDay('check_in_time', '>=', 1)
            ->whereDay('check_in_time', '<=', 31)
            ->whereMonth('check_in_time', $this->selectedMonth)  // Match current month
                ->whereYear('check_in_time', $currentYear);

$queryTimeOut->whereDay('check_out_time', '>=', 1)
            ->whereDay('check_out_time', '<=', 31)
            ->whereMonth('check_out_time', $this->selectedMonth)  // Match current month
                ->whereYear('check_out_time', $currentYear);


            $attendanceTimeIn = $queryTimeIn
            ->orderBy('student_id', 'asc')
            ->orderBy('check_in_time', 'asc')
            ->get();

            $attendanceTimeOut = $queryTimeOut
            ->orderBy('student_id', 'asc')
            ->orderBy('check_out_time', 'asc')
            ->get();


            $schools = School::all();
            $departments = Department::where('school_id', $this->selectedSchool)
                ->where('dept_identifier', 'student')
                ->get();
            

        return view('livewire.admin.show-student-attendancev2', [
            'students' => $students,
            'attendanceTimeIn' => $attendanceTimeIn,
            'attendanceTimeOut' => $attendanceTimeOut,
            'departments' => $departments,
            'courses' => $courses,
            'schoolToShow' => $this->schoolToShow,
            'departmentToShow' => $this->departmentToShow,
            'selectedCourseToShow' => $this->selectedCourseToShow,
            'selectedStudentToShow' => $this->selectedStudentToShow,
            'selectedAttendanceToShow' => $this->selectedAttendanceToShow,
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
}
