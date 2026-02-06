<?php

namespace App\Livewire\Admin;
 
use \App\Models\Admin\Employee; 
use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShowEmployeeTable extends Component
{
    use WithPagination; 
    public $search = '';
    public $sortField = 'employee_lastname';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment2 = null;
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
        $this->selectedDepartment2 = session('selectedDepartment2', null);
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
        $query = Employee::with('department')->with('school');

        // Apply search filters
        // Apply search filters
        if ($this->search) {
            $query = $this->applySearchFilters($query);
        }


        // Apply selected school filter
        if ($this->selectedSchool) {
            $query->where('school_id', $this->selectedSchool);
            $this->schoolToShow = School::find($this->selectedSchool);
        } else {
            $this->schoolToShow = null; // Reset schoolToShow if no school is selected
        }

        // Apply selected department filter
        if ($this->selectedDepartment2) {
            $query->where('department_id', $this->selectedDepartment2);
            $this->departmentToShow = Department::find($this->selectedDepartment2);
        } else {
            $this->departmentToShow = null; // Reset departmentToShow if no department is selected
        }

        $employees = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(50);

        $schools = School::all();
        // $departments = Department::where('school_id', $this->selectedSchool)->get();
        $departments = Department::where('school_id', $this->selectedSchool)
        ->where('dept_identifier', '!=', 'student')
        ->orderBy('department_abbreviation', 'asc')
        ->get();


        // Count employees by department
        $departmentCounts = Employee::select('department_id', \DB::raw('count(*) as employee_count'))
            ->groupBy('department_id')
            ->get()
            ->keyBy('department_id');

        $departmentsAll = Department::all();
        $schoolsAll = School::all();


        return view('livewire.admin.show-employee-table', [
            'employees' => $employees,
            'schools' => $schools,
            'departments' => $departments,
            'departmentCounts' => $departmentCounts,
            'departmentsAll' => $departmentsAll,
            'schoolsAll' => $schoolsAll,
            'schoolToShow' => $this->selectedSchool,
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
        $this->selectedDepartment2 = null;
        $this->departmentToShow = null;
    }

public function updateEmployeesByDepartment()
{
    if ($this->selectedDepartment2 && $this->selectedSchool) {
        $this->departmentToShow = Department::where('department_id', $this->selectedDepartment2)
                                            ->where('school_id', $this->selectedSchool)
                                            ->first();
    } else {
        $this->departmentToShow = null;
    }
}



    protected function applySearchFilters($query)
    {
        return $query->where(function (Builder $query) {
            $query->where('employee_id', 'like', '%' . $this->search . '%')
                ->orWhere('employee_firstname', 'like', '%' . $this->search . '%')
                ->orWhere('employee_middlename', 'like', '%' . $this->search . '%')
                ->orWhere('employee_lastname', 'like', '%' . $this->search . '%')
                ->orWhere('employee_rfid', 'like', '%' . $this->search . '%')
                ->orWhereHas('department', function (Builder $query) {
                    $query->where('department_abbreviation', 'like', '%' . $this->search . '%')
                        ->orWhere('department_name', 'like', '%' . $this->search . '%');
                });
        });
    }
    
}
