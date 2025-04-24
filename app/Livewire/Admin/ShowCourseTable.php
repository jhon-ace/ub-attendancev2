<?php

namespace App\Livewire\Admin;

use \App\Models\Admin\Course; 
use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShowCourseTable extends Component
{
    use WithPagination; 
    public $search = '';
    public $sortField = 'course_id';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment1 = null;
    public $departmentsToShow;
    public $schoolToShow;
    public $departmentToShow;

    protected $listeners = ['updateEmployees', 'updateEmployeesByDepartment'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (Auth::check() && Auth::user()->school) {
            $this->selectedSchool = Auth::user()->school->id;
        } else {
            $this->selectedSchool = 1;
        }
        $this->selectedDepartment1 = session('selectedDepartment1', null);
        $this->departmentsToShow = collect([]); // Initialize as an empty collection
        $this->schoolToShow = collect([]); // Initialize as an empty collection
        $this->departmentToShow = collect([]);
    }

    public function updatingSelectedSchool()
    {
        $this->resetPage();
        $this->updateEmployees();
    }

    public function updatingSelectedDepartment()
    {
        $this->resetPage();
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

    public function render()
    {
        $query = Course::with('department')->with('school');

        // Apply search filters
        $query = $this->applySearchFilters($query);

        // Apply selected school filter
        if ($this->selectedSchool) {
            $query->where('school_id', $this->selectedSchool);
            $this->schoolToShow = School::find($this->selectedSchool);
        } else {
            $this->schoolToShow = null; // Reset schoolToShow if no school is selected
        }

        // Apply selected department filter
        if ($this->selectedDepartment1) {
            $query->where('department_id', $this->selectedDepartment1);
            $this->departmentToShow = Department::find($this->selectedDepartment1);
        } else {
            $this->departmentToShow = null; // Reset departmentToShow if no department is selected
        }

        $courses = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $schools = School::all();
        // $departments = Department::where('school_id', $this->selectedSchool)->get();
        $departments = Department::where('school_id', $this->selectedSchool)
        ->where('dept_identifier', '=', 'student')
        ->get();


        // Count employees by department
        $departmentCounts = Course::select('department_id', \DB::raw('count(*) as employee_count'))
            ->groupBy('department_id')
            ->get()
            ->keyBy('department_id');

        return view('livewire.admin.show-course-table', [
            'courses' => $courses,
            'schools' => $schools,
            'departments' => $departments,
            'departmentCounts' => $departmentCounts,
        ]);
    }

    public function updateEmployees()
    {
        // Update departmentsToShow based on selected school
        if ($this->selectedSchool) {
            $this->departmentsToShow = Department::where('school_id', $this->selectedSchool)->get();
        } else {
            $this->departmentsToShow = collect(); // Reset to empty collection if no school is selected
        }

        // Ensure departmentToShow is reset if the selected school changes
        $this->selectedDepartment1 = null;
        $this->departmentToShow = null;
    }

public function updateEmployeesByDepartment()
{
    if ($this->selectedDepartment1 && $this->selectedSchool) {
        $this->departmentToShow = Department::where('department_id', $this->selectedDepartment1)
                                            ->where('school_id', $this->selectedSchool)
                                            ->first();
    } else {
        $this->departmentToShow = null;
    }
}



    protected function applySearchFilters($query)
    {
        return $query->where(function (Builder $query) {
            $query->where('course_id', 'like', '%' . $this->search . '%')
                ->orWhere('course_logo', 'like', '%' . $this->search . '%')
                ->orWhere('course_abbreviation', 'like', '%' . $this->search . '%')
                ->orWhere('course_name', 'like', '%' . $this->search . '%')
                ->orWhereHas('department', function (Builder $query) {
                $query->where('department_abbreviation', 'like', '%' . $this->search . '%')
                    ->orWhere('department_name', 'like', '%' . $this->search . '%');
            });
        });
    }
    
}
