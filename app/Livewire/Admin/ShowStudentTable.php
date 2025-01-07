<?php
namespace App\Livewire\Admin;

use App\Models\Admin\Student;
use App\Models\Admin\School;
use App\Models\Admin\Department;
use App\Models\Admin\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class ShowStudentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'course_id';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment3 = null;
    public $selectedCourse = null;
    public $departmentsToShow;
    public $schoolToShow;
    public $departmentToShow;
    public $studentsToShow;
    public $selectedCourseToShow;

    protected $listeners = ['updateEmployees', 'updateEmployeesByDepartment', 'updateStudentsByCourse'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    public function mount()
    {

        $this->selectedSchool = session('selectedSchool', null);
        $this->selectedDepartment3 = session('selectedDepartment3', null);
        $this->selectedCourse = session('selectedCourse', null);
        $this->departmentsToShow = collect([]);
        $this->schoolToShow = collect([]);
        $this->departmentToShow = collect([]);
        $this->studentsToShow = collect([]);
    }

    public function updatingSelectedSchool()
    {
        $this->resetPage();
        $this->updateEmployees();
    }

    public function updatingselectedDepartment1()
    {
        $this->resetPage();
        $this->selectedCourse = null;
        $this->departmentToShow = null;
        $this->selectedCourseToShow = null;
        $this->updateEmployeesByDepartment();
        $this-$studentsToShow = null;
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
        $query = Student::query()->with(['course.school', 'course.department']);

        // Apply search filters
        $query = $this->applySearchFilters($query);

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
        if ($this->selectedDepartment3) {
            $query->whereHas('course', function (Builder $query) {
                $query->where('department_id', $this->selectedDepartment3);
            });
            $this->departmentToShow = Department::find($this->selectedDepartment3);

            // Fetch courses for the selected department
            $courses = Course::where('department_id', $this->selectedDepartment3)->get();
            
  
        } else {
            $this->departmentToShow = null;
            $courses = Course::all(); // Fetch all courses if no department selected
        }

        // Apply selected course filter
        if ($this->selectedCourse) {
            $query->where('course_id', $this->selectedCourse);
            $this->selectedCourseToShow = Course::find($this->selectedCourse);
        } else {
            $this->selectedCourseToShow = null;
        }

        $students = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $schools = School::all();
        $departments = Department::where('school_id', $this->selectedSchool)
            ->where('dept_identifier', 'student')
            ->get();

        $studentsCounts = Student::select('course_id', \DB::raw('count(*) as student_count'))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        $coursesAll = Course::all();

        return view('livewire.admin.show-student-table', [
            'students' => $students,
            'schools' => $schools,
            'departments' => $departments,
            'studentsCounts' => $studentsCounts,
            'schoolToShow' => $this->schoolToShow,
            'departmentToShow' => $this->departmentToShow,
            'selectedCourseToShow' => $this->selectedCourseToShow,
            'courses' => $courses, // Pass the courses to the view
            'coursesAll' => $coursesAll,
        ]);
    }




    public function updateEmployees()
    {
        if ($this->selectedSchool) {
            $this->departmentsToShow = Department::where('school_id', $this->selectedSchool)->get();
        } else {
            $this->departmentsToShow = collect();
        }

        $this->selectedDepartment3 = null;
        $this->departmentToShow = null;
    }

    public function updateEmployeesByDepartment()
    {
        if ($this->selectedDepartment3 && $this->selectedSchool) {
            $this->departmentToShow = Department::where('id', $this->selectedDepartment3)
                ->where('school_id', $this->selectedSchool)
                ->first();
        } else {
            $this->departmentToShow = null;
        }
    }

    public function updateStudentsByCourse()
    {
        if ($this->selectedCourse) {
            $this->studentsToShow = Student::where('course_id', $this->selectedCourse)->get();
        } else {
            $this->studentsToShow = collect();
        }
    }

    protected function applySearchFilters($query)
    {
        return $query->where(function (Builder $query) {
            $query->where('student_id', 'like', '%' . $this->search . '%')
                ->orWhere('student_lastname', 'like', '%' . $this->search . '%')
                ->orWhere('student_firstname', 'like', '%' . $this->search . '%')
                ->orWhere('student_middlename', 'like', '%' . $this->search . '%')
                ->orWhere('student_year_grade', 'like', '%' . $this->search . '%')
                ->orWhere('student_rfid', 'like', '%' . $this->search . '%')
                ->orWhere('student_status', 'like', '%' . $this->search . '%')
                ->orWhereHas('course', function (Builder $query) {
                    $query->where('course_abbreviation', 'like', '%' . $this->search . '%')
                        ->orWhere('course_name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('course.department', function (Builder $query) {
                    $query->where('department_abbreviation', 'like', '%' . $this->search . '%')
                        ->orWhere('department_name', 'like', '%' . $this->search . '%');
                });
        });
    }
}
