<?php

namespace App\Livewire\Admin;

use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShowDepartmentTable extends Component
{
    
    use WithPagination;

    public $search = '';
    public $sortField = 'dept_identifier';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment = null;
    public $departmentsToShow;
    public $schoolToShow;

    protected $listeners = ['updateDepartments'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (Auth::check() && Auth::user()->school) {
            $this->selectedSchool = Auth::user()->school->id;
        }
        $this->departmentsToShow = collect([]); // Initialize as an empty collection
        $this->schoolToShow = collect([]); // Initialize as an empty collection
    }

    public function updatingSelectedSchool()
    {
        $this->resetPage();
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
        $query = Department::with('school');

        // Apply search filters
        $query = $this->applySearchFilters($query);
        
        // Apply selected school filter
        if ($this->selectedSchool) {
            $query->where('school_id', $this->selectedSchool);
            $this->schoolToShow = School::findOrFail($this->selectedSchool);
        } else {
            $this->schoolToShow = null; // Reset schoolToShow if no school is selected
        }

        $departments = $query->orderBy($this->sortField, $this->sortDirection)
                             ->paginate(25);

        $schools = School::all();
        //$departmentsAll = Department::paginate(20); // Adjust '10' to the number of items per page



         $departmentCounts = Department::select('school_id', \DB::raw('count(*) as department_count'))
                                  ->groupBy('school_id')
                                  ->get()
                                  ->keyBy('school_id');
        
        return view('livewire.admin.show-department-table', [
            'departments' => $departments,
            'schools' => $schools,
            'departmentCounts' => $departmentCounts,
            'schoolToShow' => $this->schoolToShow,
        ]);
    }

    public function updateDepartments()
    {
        // Update departmentsToShow based on selected school
        if ($this->selectedSchool) {
            $this->departmentsToShow = Department::where('school_id', $this->selectedSchool)
                ->get(); // Ensure this returns a collection
        } else {
            $this->departmentsToShow = collect(); // Reset to empty collection if no school is selected
        }

        
    }

    protected function applySearchFilters($query)
{
    return $query->where(function (Builder $query) {
        $query->where('id', 'like', '%' . $this->search . '%')
            ->orwhere('department_id', 'like', '%' . $this->search . '%')
            ->orWhere('department_abbreviation', 'like', '%' . $this->search . '%')
            ->orWhere('department_name', 'like', '%' . $this->search . '%')
            ->orWhere('dept_identifier', 'like', '%' . $this->search . '%')
            ->orWhereHas('school', function (Builder $query) {
                $query->where('abbreviation', 'like', '%' . $this->search . '%')
                    ->orWhere('school_name', 'like', '%' . $this->search . '%');
            });
    });
}

    
}
